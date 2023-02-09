<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\MeController;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\UserRepository;
use App\State\UserPasswordHasher;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiFilter(SearchFilter::class,
    properties: [
        'id' => 'exact',
        'email' => 'exact',
    ]
)]
#[ApiFilter(OrderFilter::class,
    properties: [
        'id',
    ]
)]
#[ApiResource(
    operations: [
        new GetCollection(
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'Only admins can access the list of users.',
        ),
        new Get(
            uriTemplate: '/users/me',
            controller: MeController::class,
            security: 'is_granted("ROLE_USER")',
            read: false,
            name: 'me'
        ),
        new Get(
            normalizationContext: ['groups' => ['user_read', 'read:User']],
            security: 'is_granted("ROLE_ADMIN") or object == user',
            securityMessage: 'Only admins can access other users.',
        ),
        new Post(processor: UserPasswordHasher::class),
        new Put(
            security: 'is_granted("ROLE_ADMIN") or object == user',
            securityMessage: 'Only admins can edit other users.',
            processor: UserPasswordHasher::class,
        ),
        new Patch(processor: UserPasswordHasher::class),
        new Delete(
            security: 'is_granted("ROLE_ADMIN") or object == user',
            securityMessage: 'Only admins can delete other users.',
        ),
    ],
    normalizationContext: ['groups' => ['user_read']],
    denormalizationContext: ['groups' => ['user_create', 'user_update']],

)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity('email', message: 'This email is already used.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[Groups(['user_read'])]
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 180)]
    #[Groups(['user_read', 'user_create', 'user_update', 'read:Bid', 'read:Bids', 'read:BidLogs', 'read:Ticket', 'read:Event', 'read:UserBid', 'read:UserBids'])]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $name = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups(['user_read', 'user_create', 'user_update', 'read:Bid', 'read:BidLogs', 'read:UserBid', 'read:UserBids'])]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private ?string $password = null;

    #[Assert\NotBlank(groups: ['user_create'])]
    #[Groups(['user_create', 'user_update'])]
    #[Assert\Length(
        min: 6,
        minMessage: 'Your password should be at least {{ limit }} characters',
        max: 4096,
        maxMessage: 'Your password cannot be longer than {{ limit }} characters'
    )]
    private ?string $plainPassword = null;

    #[Groups(['user_read'])]
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[Groups(['user_read'])]
    #[ORM\Column(type: 'integer')]
    private int $status = 0;

    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'seller', targetEntity: Bid::class)]
    private Collection $bids;

    #[ORM\OneToMany(mappedBy: 'bidder', targetEntity: BidLog::class)]
    private Collection $bidLogs;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'seller', targetEntity: UserSeller::class)]
    private Collection $userSellers;

    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Event::class)]
    private Collection $events;

    #[ORM\OneToMany(mappedBy: 'holder', targetEntity: Ticket::class)]
    private Collection $tickets;

    #[ORM\OneToMany(mappedBy: 'bidder', targetEntity: UserBid::class)]
    private Collection $userBids;

    public function __construct()
    {
        $this->bids = new ArrayCollection();
        $this->bidLogs = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->userSellers = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->tickets = new ArrayCollection();
        $this->userBids = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $painPassword): self
    {
        $this->plainPassword = $painPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getIsVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
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
            $bid->setSeller($this);
        }

        return $this;
    }

    public function removeBid(Bid $bid): self
    {
        if ($this->bids->removeElement($bid)) {
            // set the owning side to null (unless already changed)
            if ($bid->getSeller() === $this) {
                $bid->setSeller(null);
            }
        }

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
            $bidLog->setBidder($this);
        }

        return $this;
    }

    public function removeBidLog(BidLog $bidLog): self
    {
        if ($this->bidLogs->removeElement($bidLog)) {
            // set the owning side to null (unless already changed)
            if ($bidLog->getBidder() === $this) {
                $bidLog->setBidder(null);
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
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserSeller>
     */
    public function getUserSellers(): Collection
    {
        return $this->userSellers;
    }

    public function addUserSeller(UserSeller $userSeller): self
    {
        if (!$this->userSellers->contains($userSeller)) {
            $this->userSellers->add($userSeller);
            $userSeller->setSeller($this);
        }

        return $this;
    }

    public function removeUserSeller(UserSeller $userSeller): self
    {
        if ($this->userSellers->removeElement($userSeller)) {
            // set the owning side to null (unless already changed)
            if ($userSeller->getSeller() === $this) {
                $userSeller->setSeller(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setCreator($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getCreator() === $this) {
                $event->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ticket>
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets->add($ticket);
            $ticket->setHolder($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getHolder() === $this) {
                $ticket->setHolder(null);
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
            $userBid->setBidder($this);
        }

        return $this;
    }

    public function removeUserBid(UserBid $userBid): self
    {
        if ($this->userBids->removeElement($userBid)) {
            // set the owning side to null (unless already changed)
            if ($userBid->getBidder() === $this) {
                $userBid->setBidder(null);
            }
        }

        return $this;
    }
}
