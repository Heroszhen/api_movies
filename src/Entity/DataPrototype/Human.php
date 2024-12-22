<?php

namespace App\Entity\DataPrototype;

use App\Entity\Trait\TimerTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\MappedSuperclass]
abstract class Human
{
    use TimerTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "Le champs est obligatoire")]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[Assert\NotBlank(message: "Le champs est obligatoire")]
    #[ORM\Column(length: 20, nullable: true)]
    private ?string $height = null;

    #[Assert\NotBlank(message: "Le champs est obligatoire")]
    #[ORM\Column(nullable: true)]
    private ?int $age = null;

    #[Assert\NotBlank(message: "Le champs est obligatoire")]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $job = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getHeight(): ?string
    {
        return $this->height;
    }

    public function setHeight(?string $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(?string $job): static
    {
        $this->job = $job;

        return $this;
    }
}
