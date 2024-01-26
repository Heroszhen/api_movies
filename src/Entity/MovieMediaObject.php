<?php

namespace App\Entity;

use App\Entity\Trait\TimerTrait;
use App\Repository\MovieMediaObjectRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use App\Controller\Api\CreateMovieMediaObjectAction;
use ArrayObject;

#[ApiResource(
    operations: [
        new Get(),
        new GetCollection( ),
        new Post(
            security: "is_granted('ROLE_ADMIN')",
            controller: CreateMovieMediaObjectAction::class,
            deserialize: false,
            openapi: new Model\Operation(
                requestBody: new Model\RequestBody(
                    content: new ArrayObject([
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object', 
                                'properties' => [
                                    'invoiceFile' => [
                                        'type' => 'string', 
                                        'format' => 'binary',
                                        'required' => true,
                                        'description' => 'Put one photo'
                                    ]
                                ]
                            ]
                        ]
                    ])
                )
            )
        )
    ],
    normalizationContext: [
        'groups' => ['movie_photo:io', 'movie_photo:o']
    ],
    denormalizationContext: ['groups' => ['movie_photo:io', 'movie_photo:i']]
)]
#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: MovieMediaObjectRepository::class)]
class MovieMediaObject
{
    use TimerTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: true)]
    #[Groups(['movie_photo:o', 'movie:o'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['movie_photo:o', 'movie:o'])]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['movie_photo:o', 'movie:o'])]
    private ?int $size = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['movie_photo:o', 'movie:o'])]
    private ?string $mimeType = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['movie_photo:o', 'movie:o'])]
    private ?string $originalName = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    #[Groups(['movie_photo:o', 'movie:o'])]
    private ?array $dimensions = null;

    #[Vich\UploadableField(mapping: 'actress_photo', fileNameProperty: 'name', size: 'size', mimeType: 'mimeType', originalName: 'originalName', dimensions: "dimensions")]
    #[Assert\File(
        maxSize: '15M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/jpg'],
        maxSizeMessage: 'Le fichier ne doit pas dépasser {{ size }}',
        mimeTypesMessage: 'Veuillez mettre une image(jpef, jpg ou png) ou un pdf',
        groups: ['Default']
    )]
    #[Assert\NotNull(groups: ['movie_photo:i'])]
    #[Groups(['movie_photo:i'])]
    private ?File $invoiceFile = null;

    #[ORM\ManyToOne(inversedBy: 'photos')]
    private ?Movie $movie = null;

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

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): static
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(?string $originalName): static
    {
        $this->originalName = $originalName;

        return $this;
    }

    public function getDimensions(): ?array
    {
        return $this->dimensions;
    }

    public function setDimensions(?array $dimensions): static
    {
        $this->dimensions = $dimensions;

        return $this;
    }

    public function setInvoiceFile(?File $invoice = null): self
    {
        $this->invoiceFile = $invoice;

        if (null !== $invoice) {
            $this->updated = new \DateTime();
        }

        return $this;
    }

    public function getInvoiceFile(): ?File
    {
        return $this->invoiceFile;
    }

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
