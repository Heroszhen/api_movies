<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserPasswordProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly ProcessorInterface $persistProcessor,
    )
    {}

    /**
     * {@inheritDoc}
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if ($data instanceof User) {
            if (
                $operation instanceof Post ||
                ($operation instanceof Patch && str_contains($operation->getUriTemplate(), '/password'))
            ) {
                $hashedPassword = $this->passwordHasher->hashPassword(
                    $data,
                    $data->getPassword()
                );
                $data->setPassword($hashedPassword);
            }
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
