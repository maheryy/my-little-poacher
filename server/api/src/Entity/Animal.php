<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

#[ApiResource(mercure: true)]
#[ORM\Entity]
class Animal
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    public string $name = '';

    #[ORM\Column(type: 'string', length: 255)]
    public string $scientificName = '';

    #[ORM\Column(type: 'string', length: 255)]
    public string $image = '';

    #[ORM\Column(type: 'datetime')]
    public \DateTimeInterface $captureDate;

    #[ORM\Column(type: 'float')]
    public float $longitude;

    #[ORM\Column(type: 'float')]
    public float $latitude;

    #[ORM\Column(type: 'string', length: 255)]
    public string $country = '';

    #[ORM\OneToMany(mappedBy: 'bid', targetEntity: Bid::class, cascade: ['persist', 'remove'])]
    public iterable $bids;

    public function __construct()
    {
        $this->bids = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getScientificName(): string
    {
        return $this->scientificName;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getCaptureDate(): \DateTimeInterface
    {
        return $this->captureDate;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getBids(): iterable
    {
        return $this->bids;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setScientificName(string $scientificName): self
    {
        $this->scientificName = $scientificName;

        return $this;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function setCaptureDate(\DateTimeInterface $captureDate): self
    {
        $this->captureDate = $captureDate;

        return $this;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }
}