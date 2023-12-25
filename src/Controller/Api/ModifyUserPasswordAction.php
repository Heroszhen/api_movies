<?php

namespace App\Controller\Api;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsController]
final class ModifyUserPasswordAction extends AbstractController
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {}

    public function __invoke(User $user, Request $request): User
    {
        $newPassword = $user->getPassword();
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $newPassword
        );
        $user->setPassword($hashedPassword);

        return $user;
    }
}