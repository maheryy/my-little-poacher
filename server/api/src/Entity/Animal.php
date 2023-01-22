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

#[ApiFilter(SearchFilter::class,
    properties: [
        'id' => 'exact',
        'name' => 'partial',
        'scientificName' => 'partial',
        'captureDate' => 'exact',
        'latitude' => 'exact',
        'longitude' => 'exact',
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
        new Put(
            denormalizationContext: ['groups' => ['animal_write']]
        ),
        new Post(
            denormalizationContext: ['groups' => ['animal_write']]
        ),
        new Delete,
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
    #[Groups(['animal_read','animals_read','animal_write','read:Bid', 'read:Bids'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['animal_read','animals_read','animal_write','read:Bid', 'read:Bids'])]
    private ?string $scientificName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['animal_read','animals_read','animal_write','read:Bid', 'read:Bids'])]
    private ?string $image = null;

    #[ORM\Column]
    #[Groups(['animal_read','animal_write','read:Bid'])]
    private ?\DateTimeImmutable $captureDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['animal_read','animal_write','read:Bid'])]
    private ?string $longitude = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['animal_read','animal_write','read:Bid'])]
    private ?string $latitude = null;

    #[ORM\Column(length: 255)]
    #[Groups(['animal_read','animal_write','read:Bid', 'read:Bids'])]
    private ?string $country = null;

    #[ORM\OneToMany(mappedBy: 'animal', targetEntity: Bid::class)]
    #[Groups(['animal_read'])]
    private Collection $bids;

    public function __construct()
    {
        $this->bids = new ArrayCollection();
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

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

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

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
}
