<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use App\Entity\Trait\TimerTrait;
use App\Repository\ActressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\OpenApi\Model;
use App\Controller\Api\CreateActressAction;
use App\Controller\Api\UpdateActressPhotoAction;
use App\Entity\Movie;
use App\Filter\ActressFilter;
use ArrayObject;

#[Vich\Uploadable]
#[ApiResource(
    normalizationContext: [
        'groups' => ['actress:io', 'actress:o']
    ],
    denormalizationContext: ['groups' => ['actress:io', 'actress:i']],
    operations: [
        new Get(),
        new GetCollection(
            paginationEnabled: true, 
            paginationItemsPerPage: 18
        ),
        new GetCollection(
            paginationEnabled: true, 
            paginationItemsPerPage: 18,
            name: 'get_birthday_asc_custom', 
            uriTemplate: '/actresses/birthday/asc', 
            order: ['birthday' => 'ASC']
        ),
        new GetCollection(
            paginationEnabled: true, 
            paginationItemsPerPage: 18,
            name: 'get_birthday_desc_custom', 
            uriTemplate: '/actresses/birthday/desc', 
            order: ['birthday' => 'DESC']
        ),
        new GetCollection(
            paginationEnabled: false,
            name: 'get_name_asc_list', 
            uriTemplate: '/actresses/name/asc', 
            order: ['name' => 'ASC'],
            normalizationContext: ['groups' => ['list:0']]
        ),
        new Post(
            security: "is_granted('ROLE_ADMIN')",
            controller: CreateActressAction::class, 
            deserialize: false, 
            validationContext: ['groups' => ['Default', 'actress:i']], 
            openapi: new Model\Operation(
                requestBody: new Model\RequestBody(
                    content: new ArrayObject([
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object', 
                                'properties' => [
                                    'name' => [
                                        'type' => 'string', 
                                        'format' => 'string',
                                        'required' => true,
                                    ],
                                    'country' => [
                                        'type' => 'string', 
                                        'format' => 'string'
                                    ],
                                    'birthday' => [
                                        'type' => 'string', 
                                        'format' => 'date-time'
                                    ],
                                    'description' => [
                                        'type' => 'string', 
                                        'format' => 'string'
                                    ],
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
        ),
        new Post(
            security: "is_granted('ROLE_ADMIN')",
            controller: UpdateActressPhotoAction::class,
            uriTemplate: '/actresses/{id}/update-photo',
            deserialize: false, 
            validationContext: ['groups' => ['Default', 'actress:i']], 
        ),
        new Patch(
            security: "is_granted('ROLE_ADMIN')",
            validationContext: ['groups' => ['Default', 'actress:i']], 
        )
    ]
)]
// #[ApiResource(paginationEnabled: true, paginationItemsPerPage: 18, paginationMaximumItemsPerPage: 18)]
#[ApiFilter(ActressFilter::class, properties:['name' => 'partial', 'country' => 'partial'])]
#[ORM\Entity(repositoryClass: ActressRepository::class)]
#[UniqueEntity('name')]
class Actress
{
    use TimerTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: true)]
    #[Groups(['actress:o', 'movie:o', 'list:0'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(allowNull: false)]
    #[Groups(['actress:io', 'movie:o', 'list:0'])]
    private ?string $name = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['actress:io'])]
    private ?string $country = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['actress:io'])]
    private ?\DateTimeInterface $birthday = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['actress:io'])]
    private ?string $description = null;

    // #[ORM\Embedded(class: EmbeddedFile::class)]
    // #[Groups(['actress:o'])]
    // private ?EmbeddedFile $invoice = null;

    #[Vich\UploadableField(mapping: 'actress_photo', fileNameProperty: 'photoName', size: 'photoSize', mimeType: 'photoMimeType', originalName: 'photoOriginalName', dimensions: "photoDimensions")]
    #[Assert\File(
        maxSize: '15M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/jpg'],
        maxSizeMessage: 'Le fichier ne doit pas dépasser {{ size }}',
        mimeTypesMessage: 'Veuillez mettre une image(jpef, jpg ou png) ou un pdf',
        groups: ['Default']
    )]
    #[Groups(['actress:i'])]
    // #[Assert\NotNull(groups: ['actress:i'])]
    private ?File $invoiceFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['actress:o'])]
    private ?string $photoName = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['actress:o'])]
    private ?int $photoSize = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['actress:o'])]
    private ?string $photoMimeType = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['actress:o'])]
    private ?string $photoOriginalName = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    #[Groups(['actress:o'])]
    private ?array $photoDimensions = null;

    #[ORM\ManyToMany(targetEntity: Movie::class, mappedBy: 'actors')]
    private Collection $movies;

    public function __construct()
    {
        // $this->invoice = new EmbeddedFile();
        $this->movies = new ArrayCollection();
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

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): static
    {
        $this->birthday = $birthday;

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

    // public function setInvoice(EmbeddedFile $invoice): self
    // {
    //     $this->invoice = $invoice;

    //     return $this;
    // }

    // public function getInvoice(): EmbeddedFile
    // {
    //     return $this->invoice;
    // }

    // #[Assert\Callback]
    // public function validate(ExecutionContextInterface $context, $payload)
    // {
    //     if (null === $this->invoice->getName() && null === $this->invoiceFile) {
    //         $context->buildViolation('Veuillez uploader une preuve d\'achat.')
    //             ->atPath('invoiceFile')
    //             ->addViolation();
    //     }
    // }

    public function getPhotoName(): ?string
    {
        return $this->photoName;
    }

    public function setPhotoName(?string $photoName): static
    {
        $this->photoName = $photoName;

        return $this;
    }

    public function getPhotoSize(): ?int
    {
        return $this->photoSize;
    }

    public function setPhotoSize(?int $photoSize): static
    {
        $this->photoSize = $photoSize;

        return $this;
    }

    public function getPhotoMimeType(): ?string
    {
        return $this->photoMimeType;
    }

    public function setPhotoMimeType(?string $photoMimeType): static
    {
        $this->photoMimeType = $photoMimeType;

        return $this;
    }

    public function getPhotoOriginalName(): ?string
    {
        return $this->photoOriginalName;
    }

    public function setPhotoOriginalName(?string $photoOriginalName): static
    {
        $this->photoOriginalName = $photoOriginalName;

        return $this;
    }

    public function getPhotoDimensions(): ?array
    {
        return $this->photoDimensions;
    }

    public function setPhotoDimensions(?array $photoDimensions): static
    {
        $this->photoDimensions = $photoDimensions;

        return $this;
    }

    /**
     * @return Collection<int, Movie>
     */
    public function getMovies(): Collection
    {
        return $this->movies;
    }

    public function addMovie(Movie $movie): static
    {
        if (!$this->movies->contains($movie)) {
            $this->movies->add($movie);
            $movie->addActor($this);
        }

        return $this;
    }

    public function removeMovie(Movie $movie): static
    {
        if ($this->movies->removeElement($movie)) {
            $movie->removeActor($this);
        }

        return $this;
    }
}
