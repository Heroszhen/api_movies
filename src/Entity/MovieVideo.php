<?php

namespace App\Entity;

use App\Repository\MovieVideoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;

#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            security: "is_granted('ROLE_ADMIN')",
        ),
        new Patch(
            security: "is_granted('ROLE_ADMIN')",
        )
    ],
    normalizationContext: [
        'groups' => ['movie_video:io', 'movie_video:o']
    ],
    denormalizationContext: ['groups' => ['movie_video:io', 'movie_video:i']]
)]
#[ORM\Entity(repositoryClass: MovieVideoRepository::class)]
class MovieVideo extends AbstractVideo
{
    #[ORM\ManyToOne(inversedBy: 'videos')]
    #[Groups(['movie_video:io'])]
    private ?Movie $movie = null;

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): static
    {
        $this->movie = $movie;

        return $this;
    }
}
