<?php

namespace App\Entity;

use App\Entity\Actress;
use App\Entity\Trait\TimerTrait;
use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(
            paginationEnabled: true,
            paginationItemsPerPage: 20,
            order: ['name' => 'ASC']
        ),
        new Post(
            security: "is_granted('ROLE_ADMIN')",
        ),
        new Patch(
            security: "is_granted('ROLE_ADMIN')",
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN')",
            write: true
        )
    ],
    normalizationContext: [
        'groups' => ['movie:io', 'movie:o']
    ],
    denormalizationContext: ['groups' => ['movie:io', 'movie:i']]
)]
#[ApiResource(order: ['name' => 'ASC'])]
#[ORM\Entity(repositoryClass: MovieRepository::class)]
#[UniqueEntity('name')]
class Movie
{
    use TimerTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: true)]
    #[Groups(['movie:o', 'movie_video:io'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['movie:io'])]
    #[Assert\NotBlank(allowNull: false)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Actress::class, inversedBy: 'movies')]
    #[Groups(['movie:io'])]
    private Collection $actors;

    #[ORM\Column]
    #[Groups(['movie:io'])]
    private ?int $last = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['movie:io'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['movie:io'])]
    private ?\DateTimeInterface $released = null;

    #[ORM\OneToMany(mappedBy: 'movie', targetEntity: MovieMediaObject::class, cascade: ['persist', 'remove'])]
    #[Groups(['movie:io'])]
    private Collection $photos;

    #[ORM\OneToMany(mappedBy: 'movie', targetEntity: MovieVideo::class, cascade: ['persist', 'remove'])]
    #[Groups(['movie:io'])]
    private Collection $videos;

    public function __construct()
    {
        $this->actors = new ArrayCollection();
        $this->photos = new ArrayCollection();
        $this->videos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Actress>
     */
    public function getActors(): Collection
    {
        return $this->actors;
    }

    public function addActor(Actress $actor): static
    {
        if (!$this->actors->contains($actor)) {
            $this->actors->add($actor);
        }

        return $this;
    }

    public function removeActor(Actress $actor): static
    {
        $this->actors->removeElement($actor);

        return $this;
    }

    public function getLast(): ?int
    {
        return $this->last;
    }

    public function setLast(int $last): static
    {
        $this->last = $last;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getReleased(): ?\DateTimeInterface
    {
        return $this->released;
    }

    public function setReleased(?\DateTimeInterface $released): static
    {
        $this->released = $released;

        return $this;
    }

    /**
     * @return Collection<int, MovieMediaObject>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(MovieMediaObject $photo): static
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setMovie($this);
        }

        return $this;
    }

    public function removePhoto(MovieMediaObject $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getMovie() === $this) {
                $photo->setMovie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MovieVideo>
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(MovieVideo $video): static
    {
        if (!$this->videos->contains($video)) {
            $this->videos->add($video);
            $video->setMovie($this);
        }

        return $this;
    }

    public function removeVideo(MovieVideo $video): static
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getMovie() === $this) {
                $video->setMovie(null);
            }
        }

        return $this;
    }
}
