<?php

namespace App\Entity\DataPrototype;

use App\Repository\DataPrototype\GirlRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\DataPrototype\Human;

#[ORM\Entity(repositoryClass: GirlRepository::class)]
class Girl extends Human
{   
    /**
     * cup size: A , B etc
     */
    #[ORM\Column(length: 5, nullable: true)]
    private ?string $cup = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'girlFriends')]
    private ?Man $man = null;

    public function getCup(): ?string
    {
        return $this->cup;
    }

    public function setCup(?string $cup): static
    {
        $this->cup = $cup;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getMan(): ?Man
    {
        return $this->man;
    }

    public function setMan(?Man $man): static
    {
        $this->man = $man;

        return $this;
    }
}
