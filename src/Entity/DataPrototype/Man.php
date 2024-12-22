<?php

namespace App\Entity\DataPrototype;

use App\Repository\DataPrototype\ManRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\DataPrototype\Human;

#[ORM\Entity(repositoryClass: ManRepository::class)]
class Man extends Human
{
    #[ORM\OneToMany(mappedBy: 'man', targetEntity: Girl::class, cascade: ['persist'])]
    private Collection $girlFriends;

    public function __construct()
    {
        $this->girlFriends = new ArrayCollection();
    }

    /**
     * @return Collection<int, Girl>
     */
    public function getGirlFriends(): Collection
    {
        return $this->girlFriends;
    }

    public function addGirlFriend(Girl $girlFriend): static
    {
        if (!$this->girlFriends->contains($girlFriend)) {
            $this->girlFriends->add($girlFriend);
            $girlFriend->setMan($this);
        }

        return $this;
    }

    public function removeGirlFriend(Girl $girlFriend): static
    {
        if ($this->girlFriends->removeElement($girlFriend)) {
            // set the owning side to null (unless already changed)
            if ($girlFriend->getMan() === $this) {
                $girlFriend->setMan(null);
            }
        }

        return $this;
    }
}
