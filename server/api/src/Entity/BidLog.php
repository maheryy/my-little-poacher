<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
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
use Symfony\Component\Validator\Constraints as Assert;

#[ApiFilter(SearchFilter::class,
    properties: [
        'id' => 'exact',
        'price' => 'exact',
        'bid' => 'exact',
        'bidder' => 'exact',
    ]
)]
#[ApiFilter(DateFilter::class,
    properties: [
        'createdAt',
    ]
)]
#[ApiFilter(NumericFilter::class,
    properties: [
        'price',
    ]
)]
#[ApiFilter(OrderFilter::class,
    properties: [
        'price',
        'createdAt',
    ]
)]
#[ApiResource(
    normalizationContext: ['groups' => ['bidLogs_read', 'bidLog_read']],
    denormalizationContext: ['groups' => ['bidLog_write']],
    paginationItemsPerPage: 20,
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['bidLogs_read', 'bidLog_read', 'read:BidLogs']],
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')",
            securityMessage: 'Only authenticated users can view bid logs.'
        ),
        new Get(
            normalizationContext: ['groups' => ['bidLog_read', 'read:BidLog']],
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')",
            securityMessage: 'Only authenticated users can view bid logs.'
        ),
        new Put(
            denormalizationContext: ['groups' => ['bidLog_write']],
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: 'Only admins can edit bid logs.'
        ),
        new Post(
            denormalizationContext: ['groups' => ['bidLog_write']],
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: 'Only admins can delete bid logs.'
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
    #[Groups(['bidLogs_read', 'bidLog_read', 'bidLog_write', 'read:Bid', 'read:Bids'])]
    #[Assert\NotBlank]
    #[Assert\Positive(message: 'The price must be positive')]
    #[Assert\Range(
        min: 5, max: 1000000,
        notInRangeMessage: 'The price must be between 0 and 1000000',
    )]
    private ?int $price = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(['bidLogs_read', 'bidLog_read', 'bidLog_write', 'read:Bid', 'read:Bids'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'bidLogs', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['bidLogs_read', 'bidLog_read', 'bidLog_write'])]
    private ?Bid $bid = null;

    #[ORM\ManyToOne(inversedBy: 'bidLogs', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['bidLogs_read', 'bidLog_read', 'bidLog_write', 'read:Bid', 'read:Bids'])]
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
