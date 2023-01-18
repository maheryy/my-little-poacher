<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\BidLogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: BidLogRepository::class)]
class BidLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\ManyToOne(inversedBy: 'bidLogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Bid $bid = null;

    #[ORM\ManyToOne(inversedBy: 'bidLogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $bidder = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getBid(): ?Bid
    {
        return $this->bid;
    }

    public function setBid(?Bid $bid): self
    {
        $this->bid = $bid;

        return $this;
    }

    public function getBidder(): ?User
    {
        return $this->bidder;
    }

    public function setBidder(?User $bidder): self
    {
        $this->bidder = $bidder;

        return $this;
    }
}
