<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

#[ApiResource(mercure: true)]
#[ORM\Entity]
class BidLog
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(type: 'integer')]
    public int $price = 0;

    #[ORM\ManyToOne(inversedBy: 'logs', targetEntity: Bid::class)]
    public Bid $bid;

    #[ORM\ManyToOne(inversedBy: 'logs', targetEntity: User::class)]
    public User $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getBid(): Bid
    {
        return $this->bid;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}