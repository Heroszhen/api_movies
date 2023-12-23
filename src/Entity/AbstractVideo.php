<?php

namespace App\Entity;

use App\Entity\Trait\TimerTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Metadata\ApiProperty;

#[ORM\Entity]
#[ORM\MappedSuperclass]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorMap(['movie_video' => MovieVideo::class])]
#[UniqueEntity(['title'])]
class AbstractVideo
{
    use TimerTrait;

    const LINK_TYPES = [
        'embed',
        'video_url',
        'page_url'
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: true)]
    #[Groups(['movie:io', 'movie:o', 'movie_video:o'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['movie_video:io', 'movie:o'])]
    #[Assert\NotBlank(allowNull: false)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['movie_video:io', 'movie:o'])]
    #[Assert\NotBlank(allowNull: false)]
    private ?string $link = null;

    #[ORM\Column(length: 30)]
    #[Groups(['movie_video:io', 'movie:o'])]
    #[Assert\NotBlank(allowNull: false)]
    private ?string $linkType = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['movie_video:io', 'movie:o'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\NotBlank(allowNull: false)]
    #[Groups(['movie_video:io', 'movie:o'])]
    private ?int $last = null;

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function getLinkType(): ?string
    {
        return $this->linkType;
    }

    public function setLinkType(string $linkType): static
    {
        $this->linkType = $linkType;

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

    public function getLast(): ?int
    {
        return $this->last;
    }

    public function setLast(int $last): static
    {
        $this->last = $last;

        return $this;
    }
}
