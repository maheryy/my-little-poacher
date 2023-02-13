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
use App\Controller\EndBidController;
use App\Repository\BidRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiFilter(DateFilter::class,
    properties:[
        'endAt' => DateFilter::EXCLUDE_NULL,
        'createdAt' => DateFilter::EXCLUDE_NULL,
    ]
)]
#[ApiFilter(OrderFilter::class,
    properties: [
        'endAt',
        'createdAt',
    ]
)]
#[ApiFilter(SearchFilter::class,
    properties: [
        'id' => 'exact',
        'title' => 'exact',
        'status' => 'exact',
        'animal.name' => 'exact',
        'seller' => 'exact',
])]
#[ApiFilter(NumericFilter::class,
    properties: [
        'initialPrice',
        'currentPrice',
    ])]
#[ApiResource(
    normalizationContext: ['groups' => ['bids_read', 'bid_read']],
    denormalizationContext: ['groups' => ['bid_write']],
    paginationItemsPerPage: 12,
    paginationMaximumItemsPerPage: 12,
    paginationClientEnabled: true,
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['bids_read', 'bid_read', 'read:Bids']]
        ),
        new Get(
            normalizationContext: ['groups' => ['bid_read', 'read:Bid']]
        ),
        new Put(
            uriTemplate: '/bids/{id}/end',
            controller: EndBidController::class,
            denormalizationContext: ['groups' => ['bid_write']],
            read: false,
            security: 'is_granted("ROLE_SELLER")',
            securityMessage: 'Only the seller can end the bid.',
            openapiContext: [
                'summary' => 'End the bid',
                'tags' => ['Bid'],
                'description' => 'End the bid',
            ],
        ),
        new Post(
            denormalizationContext: ['groups' => ['bid_write']],
            security: 'is_granted("ROLE_ADMIN") or is_granted("ROLE_USER")',
            securityMessage: 'Only the seller can create the bid.'
        ),
        new Delete(
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'Only the admin can delete the bid.'
        ),
        new Patch(
            denormalizationContext: ['groups' => ['bid_patch']],
            inputFormats: ['json' => ['application/json']],
            security: 'is_granted("ROLE_ADMIN") or is_granted("ROLE_USER")',
            securityMessage: 'Only the seller can edit the bid.'
        )
    ]
)]
#[ORM\Entity(repositoryClass: BidRepository::class)]
class Bid
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['bid_read','bids_read', 'read:BidLog', 'read:BidLogs', 'read:UserBid', 'read:UserBids'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['bid_read','bids_read', 'bid_write', 'read:BidLog', 'read:BidLogs', 'read:UserBid', 'read:UserBids'])]
    #[Assert\Length(
        min: 3, minMessage: 'The title must be at least 3 characters long',
        max: 255, maxMessage: 'The title cannot be longer than 255 characters'
    )]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(['bid_read', 'bid_write', 'read:BidLog', 'read:BidLogs', 'read:UserBid', 'read:UserBids'])]
    #[Assert\Length(
        min: 3, minMessage: 'The description must be at least 3 characters long',
        max: 600, maxMessage: 'The description cannot be longer than 600 characters'
    )]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['bid_read', 'bid_write','read:BidLog', 'read:BidLogs', 'read:UserBid', 'read:UserBids'])]
    #[Assert\NotBlank]
    #[Assert\Positive(message: 'The price must be positive')]
    #[Assert\Range(
        min: 5, max: 1000000,
        notInRangeMessage: 'The initial price must be between 0 and 1000000',
    )]
    private ?int $initialPrice = null;

    #[ORM\Column]
    #[Groups(['bid_read', 'bid_write','bid_patch', 'read:BidLog', 'read:BidLogs', 'read:UserBid', 'read:UserBids'])]
    #[Assert\NotBlank]
    #[Assert\Positive(message: 'The price must be positive')]
    #[Assert\Range(
        min: 5, max: 1000000,
        notInRangeMessage: 'The current price must be between 0 and 1000000',
    )]
    private ?int $currentPrice = null;

    #[ORM\Column]
    #[Groups(['bid_read', 'bid_write', 'read:BidLog', 'read:BidLogs', 'read:UserBid', 'read:UserBids'])]
    private ?\DateTimeImmutable $endAt = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Groups(['bid_read'])]
    private ?int $status = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(['bid_read','bids_read', 'read:BidLog', 'read:BidLogs', 'read:UserBid', 'read:UserBids'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(['bid_read','bids_read', 'read:BidLog', 'read:BidLogs', 'read:UserBid', 'read:UserBids'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'bids')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['bids_read','bid_read', 'read:BidLog', 'read:BidLogs', 'read:UserBid', 'read:UserBids'])]
    private ?Animal $animal = null;

    #[ORM\ManyToOne(inversedBy: 'bids')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['bids_read','bid_read', 'read:BidLog', 'read:BidLogs', 'read:UserBid', 'read:UserBids'])]
    private ?User $seller = null;

    #[ORM\OneToMany(mappedBy: 'bid', targetEntity: BidLog::class)]
    #[Groups(['bid_read'])]
    private Collection $bidLogs;

    #[ORM\OneToMany(mappedBy: 'bid', targetEntity: Comment::class)]
    #[Groups(['bid_read'])]
    private Collection $comments;

    #[Groups(['bid_read'])]
    #[ORM\OneToMany(mappedBy: 'bid', targetEntity: UserBid::class)]
    private Collection $userBids;

    public function __construct()
    {
        $this->bidLogs = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->userBids = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getInitialPrice(): ?int
    {
        return $this->initialPrice;
    }

    public function setInitialPrice(int $initialPrice): self
    {
        $this->initialPrice = $initialPrice;

        return $this;
    }

    public function getCurrentPrice(): ?int
    {
        return $this->currentPrice;
    }

    public function setCurrentPrice(int $currentPrice): self
    {
        $this->currentPrice = $currentPrice;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeImmutable $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $animal): self
    {
        $this->animal = $animal;

        return $this;
    }

    public function getSeller(): ?User
    {
        return $this->seller;
    }

    public function setSeller(?User $seller): self
    {
        $this->seller = $seller;

        return $this;
    }

    /**
     * @return Collection<int, BidLog>
     */
    public function getBidLogs(): Collection
    {
        return $this->bidLogs;
    }

    public function addBidLog(BidLog $bidLog): self
    {
        if (!$this->bidLogs->contains($bidLog)) {
            $this->bidLogs->add($bidLog);
            $bidLog->setBid($this);
        }

        return $this;
    }

    public function removeBidLog(BidLog $bidLog): self
    {
        if ($this->bidLogs->removeElement($bidLog)) {
            // set the owning side to null (unless already changed)
            if ($bidLog->getBid() === $this) {
                $bidLog->setBid(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setBid($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getBid() === $this) {
                $comment->setBid(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserBid>
     */
    public function getUserBids(): Collection
    {
        return $this->userBids;
    }

    public function addUserBid(UserBid $userBid): self
    {
        if (!$this->userBids->contains($userBid)) {
            $this->userBids->add($userBid);
            $userBid->setBid($this);
        }

        return $this;
    }

    public function removeUserBid(UserBid $userBid): self
    {
        if ($this->userBids->removeElement($userBid)) {
            // set the owning side to null (unless already changed)
            if ($userBid->getBid() === $this) {
                $userBid->setBid(null);
            }
        }

        return $this;
    }

}
