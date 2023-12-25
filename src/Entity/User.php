<?php

namespace App\Entity;

use App\Entity\Trait\TimerTrait;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use App\Controller\Api\ModifyUserPasswordAction;
use Symfony\Component\Serializer\Annotation\Groups;
use Ramsey\Uuid\UuidInterface as Uuid;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ApiResource(
    operations: [
        new Get(
            security: "is_granted('ROLE_ADMIN')",
        ),
        new GetCollection(),
        new Patch(
            name: 'Update',
            description: 'Update user password',
            security: "is_granted('ROLE_ADMIN') or object.id == user.id",
            uriTemplate: '/users/{id}/password', 
            controller: ModifyUserPasswordAction::class,
            normalizationContext: ['groups' => ['user_password:o']],
            denormalizationContext: ['groups' => ['user_password:i']],
        ),
        new Patch(
            security: "is_granted('ROLE_ADMIN') or object.id == user.id",
            denormalizationContext: ['groups' => ['user_password:io']],
        )
    ],
    normalizationContext: ['groups' => ['user:io', 'user:o']],
    denormalizationContext: ['groups' => ['user:io', 'user:i']]
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimerTrait;

    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[Assert\Uuid]
    #[ApiProperty(identifier: true)]
    #[Groups(['user:o', 'user_password:o'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(allowNull: false)]
    #[Groups(['user:o', 'user_password:io'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['user:o', 'user_password:io'])]
    private array $roles = ["ROLE_USER"];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(allowNull: false)]
    #[Assert\Length(min: 8)]
    #[Groups(['user_password:i'])]
    private ?string $password = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['user:o', 'user_password:io'])]
    private ?string $lastname = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['user:o', 'user_password:io'])]
    private ?string $firstname = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }
}
