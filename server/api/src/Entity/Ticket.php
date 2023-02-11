<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use App\Enum\TicketStatus;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiFilter(
    DateFilter::class,
    properties: [
        'createdAt' => DateFilter::EXCLUDE_NULL,
        'expireAt' => DateFilter::EXCLUDE_NULL,
    ]
)]
#[ApiFilter(
    OrderFilter::class,
    properties: [
        'createdAt',
        'expireAt'
    ]
)]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        'id' => 'exact',
        'reference' => 'exact',
        'status' => 'exact',
        'event' => 'exact',
        'holder' => 'exact',
    ]
)]
#[ApiResource(
    normalizationContext: ['groups' => ['tickets_read', 'read:Tickets']],
    denormalizationContext: ['groups' => ['ticket_write']],
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['ticket_read', 'tickets_read', 'read:Tickets']],
        ),
        new Get(
            normalizationContext: ['groups' => ['ticket_read', 'read:Ticket']],
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_USER') and object.getHolder() == user",
            securityMessage: "Only the ticket holder can access this resource."
        ),
        new Post(
            denormalizationContext: ['groups' => ['ticket_write']],
        ),
        new Patch(
            denormalizationContext: ['groups' => ['ticket_patch']],
            inputFormats: ['json' => ['application/json']]
        )
    ]
)]
#[ORM\Entity(repositoryClass: TicketRepository::class)]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['tickets_read', 'ticket_read'])]
    private int $id;

    #[ORM\Column(length: 255)]
    #[Groups(['tickets_read', 'ticket_read', 'read:Event'])]
    #[Assert\NotBlank]
    private string $reference;

    #[ORM\Column(length: 255, type: "string", enumType: TicketStatus::class)]
    #[Groups(['tickets_read', 'ticket_read', 'read:Event', 'ticket_patch'])]
    private TicketStatus $status;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(['ticket_read', 'tickets_read'])]
    private \DateTimeImmutable $expireAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(['tickets_read', 'ticket_read'])]
    private \DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['tickets_read', 'ticket_read', 'ticket_write'])]
    private Event $event;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['tickets_read', 'ticket_read', 'read:Event'])]
    private User $holder;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getStatus(): TicketStatus
    {
        return $this->status;
    }

    public function setStatus(TicketStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getExpireAt(): \DateTimeImmutable
    {
        return $this->expireAt;
    }

    public function setExpireAt(\DateTimeImmutable $expireAt): self
    {
        $this->expireAt = $expireAt;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function setEvent(Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getHolder(): User
    {
        return $this->holder;
    }

    public function setHolder(User $holder): self
    {
        $this->holder = $holder;

        return $this;
    }
}
