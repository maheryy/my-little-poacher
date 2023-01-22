<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\UserBidRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiFilter(SearchFilter::class,
    properties: [
        'id' => 'exact',
        'status' => 'exact',
    ]
)]
#[ApiResource(
    normalizationContext: ['groups' => ['userBids_read', 'userBid_read']],
    denormalizationContext: ['groups' => ['userBid_write']],
    paginationItemsPerPage: 12,
    paginationMaximumItemsPerPage: 12,
    paginationClientEnabled: true,
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['userBids_read', 'userBid_read', 'read:UserBids']]
        ),
        new Get(
            normalizationContext: ['groups' => ['userBid_read', 'read:UserBid']]
        ),
        new Put(
            denormalizationContext: ['groups' => ['userBid_write']]
        ),
        new Post(
            denormalizationContext: ['groups' => ['userBid_write']]
        ),
    ]
)]
#[ORM\Entity(repositoryClass: UserBidRepository::class)]
class UserBid
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['userBid_read', 'userBids_read', 'read:Bid', 'read:Bids'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['userBid_read', 'userBids_read', 'userBid_write', 'read:Bid', 'read:Bids'])]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'userBids')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['userBid_read', 'userBids_read', 'read:Bid', 'read:Bids'])]
    private ?User $bidder = null;

    #[ORM\ManyToOne(inversedBy: 'userBids')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['userBid_read', 'userBids_read', 'read:Bid', 'read:Bids'])]
    private ?Bid $bid = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

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

    public function getBid(): ?Bid
    {
        return $this->bid;
    }

    public function setBid(?Bid $bid): self
    {
        $this->bid = $bid;

        return $this;
    }
}
