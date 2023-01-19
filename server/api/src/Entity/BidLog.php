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
use ApiPlatform\Metadata\Patch;
use App\Repository\BidLogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiFilter(SearchFilter::class,
    properties: [
        'price' => 'exact',
        'bid.id' => 'exact',
        'bidder.id' => 'exact',
    ]
)]
#[ApiResource(
    normalizationContext: ['groups' => ['bidLogs_read', 'read:BidLog']],
    denormalizationContext: ['groups' => ['bidLog_write']],
    paginationItemsPerPage: 20,
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['bidLogs_read', 'bidLog_read']],
        ),
        new Get(
            security: "is_granted('ROLE_ADMIN') or object.getBidder() == user",
            normalizationContext: ['groups' => ['bidLog_read', 'read:BidLog']]
        ),
        new Put(),
        new Post(
            denormalizationContext: ['groups' => ['bidLog_write']]
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN')",
        ),
    ]
)]
#[ORM\Entity(repositoryClass: BidLogRepository::class)]
class BidLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['bidLog_read','bidLogs_read' ])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['bidLogs_read', 'bidLog_read', 'bidLog_write', 'read:Bid'])]
    private ?int $price = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(['bidLogs_read', 'bidLog_read', 'bidLog_write', 'read:Bid'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'bidLogs', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['bidLogs_read', 'bidLog_read', 'bidLog_write'])]
    private ?Bid $bid = null;

    #[ORM\ManyToOne(inversedBy: 'bidLogs', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['bidLogs_read', 'bidLog_read', 'bidLog_write', 'read:Bid'])]
    private ?User $bidder = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

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
