<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

#[ApiResource(mercure: true)]
#[ORM\Entity]
class Bid
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private ?int $id = null;
    
    #[ORM\Column(type: 'string', length: 255)]
    public string $title = '';

    #[ORM\Column(type: 'string', length: 255)]
    public string $slug = '';

    #[ORM\Column(type: 'text')]
    public string $description = '';

    #[ORM\Column(type: 'integer')]
    public int $status = 0;

    #[ORM\Column(type: 'integer')]
    public int $initialPrice = 0;

    #[ORM\Column]
    public ?\DateTimeInterface $startAt = null;

    #[ORM\Column]
    public ?\DateTimeInterface $endAt = null;

    #[ORM\ManyToOne(inversedBy: 'bids', targetEntity: User::class)]
    public User $seller;

    #[ORM\ManyToOne(inversedBy: 'bids', targetEntity: Animal::class)]
    public Animal $animal;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getInitialPrice(): int
    {
        return $this->initialPrice;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function getSeller(): User
    {
        return $this->seller;
    }

    public function getAnimal(): Animal
    {
        return $this->animal;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function setInitialPrice(int $initialPrice): self
    {
        $this->initialPrice = $initialPrice;

        return $this;
    }

    public function setStartAt(?\DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function setEndAt(?\DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function setSeller(User $seller): self
    {
        $this->seller = $seller;

        return $this;
    }

    public function setAnimal(Animal $animal): self
    {
        $this->animal = $animal;

        return $this;
    }

    public function __toString(): string
    {
        return $this->title;
    }

}