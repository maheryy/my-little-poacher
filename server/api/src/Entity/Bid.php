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

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'bids')]
    public User $seller;

    #[ORM\ManyToOne(targetEntity: Animal::class, inversedBy: 'bids')]
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

}