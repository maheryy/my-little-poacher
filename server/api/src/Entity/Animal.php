<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use App\Repository\AnimalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiFilter(SearchFilter::class,
    properties: [
        'id' => 'exact',
        'name' => 'partial',
        'scientificName' => 'partial',
        'captureDate' => 'exact',
        'country' => 'exact',
])]
#[ApiResource(
    normalizationContext: ['groups' => ['animal_read', 'animals_read']],
    denormalizationContext: ['groups' => ['animal_write']],
    paginationItemsPerPage: 12,
    paginationMaximumItemsPerPage: 12,
    paginationClientEnabled: true,
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['animals_read', 'animal_read']]
        ),
        new Get(
            normalizationContext: ['groups' => ['animal_read']]
        ),
        new Post(
            denormalizationContext: ['groups' => ['animal_write']],
            security: 'is_granted("ROLE_ADMIN") or is_granted("ROLE_SELLER")',
            securityMessage: 'Only the seller can create the animal.'
        ),
        new Put(
            denormalizationContext: ['groups' => ['animal_write']],
            security: 'is_granted("ROLE_ADMIN")'
        ),
        new Delete(
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'Only admins can delete animals.'
        )
    ]
)]
#[ORM\Entity(repositoryClass: AnimalRepository::class)]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['animal_read','animals_read','read:Bid'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message: 'The name of the animal is required'
    )]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'The name of the animal must be at least 3 characters long',
        maxMessage: 'The name of the animal cannot be longer than 255 characters'
    )]
    #[Groups(['animal_read','animals_read','animal_write','read:Bid', 'read:Bids'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message: 'The scientific name of the animal is required'
    )]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'The scientific name of the animal must be at least 3 characters long',
        maxMessage: 'The scientific name of the animal cannot be longer than 255 characters'
    )]
    #[Groups(['animal_read','animals_read','animal_write','read:Bid', 'read:Bids'])]
    private ?string $scientificName = null;

    #[ORM\Column]
    #[Groups(['animal_read','animal_write','read:Bid'])]
    private ?\DateTimeImmutable $captureDate = null;

    #[ORM\Column(length: 255)]
    #[Groups(['animal_read','animal_write','read:Bid', 'read:Bids'])]
    #[Assert\NotBlank(
        message: 'The country of the animal is required'
    )]
    private ?string $country = null;

    #[ORM\OneToMany(mappedBy: 'animal', targetEntity: Bid::class)]
    #[Groups(['animal_read'])]
    private Collection $bids;

    #[ORM\OneToMany(mappedBy: 'animal', targetEntity: Image::class)]
    private Collection $images;

    public function __construct()
    {
        $this->bids = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getScientificName(): ?string
    {
        return $this->scientificName;
    }

    public function setScientificName(string $scientificName): self
    {
        $this->scientificName = $scientificName;

        return $this;
    }

    public function getCaptureDate(): ?\DateTimeInterface
    {
        return $this->captureDate;
    }

    public function setCaptureDate(\DateTimeInterface $captureDate): self
    {
        $this->captureDate = $captureDate;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<int, Bid>
     */
    public function getBids(): Collection
    {
        return $this->bids;
    }

    public function addBid(Bid $bid): self
    {
        if (!$this->bids->contains($bid)) {
            $this->bids->add($bid);
            $bid->setAnimal($this);
        }

        return $this;
    }

    public function removeBid(Bid $bid): self
    {
        if ($this->bids->removeElement($bid)) {
            // set the owning side to null (unless already changed)
            if ($bid->getAnimal() === $this) {
                $bid->setAnimal(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setAnimal($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getAnimal() === $this) {
                $image->setAnimal(null);
            }
        }

        return $this;
    }
}
