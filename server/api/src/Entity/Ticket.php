<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Type;

#[ApiResource(
    normalizationContext: ['groups' => ['tickets_read']],
    denormalizationContext: ['groups' => ['ticket_write']],
    operations : [
        new GetCollection(
            normalizationContext : ['groups' => ['ticket_read', 'tickets_read', 'read:Tickets']],
        ),
        new Get(
            normalizationContext : ['groups' => ['ticket_read', 'read:Ticket']]
        ),
        new Put(
            denormalizationContext: ['groups' => ['ticket_write']]
        ),
        new Post(
            denormalizationContext: ['groups' => ['ticket_write']]
        )
    ]

)]
#[ORM\Entity(repositoryClass: TicketRepository::class)]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 5, max: 255)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Groups(['tickets_read', 'ticket_read','ticket_write', 'read:Event'])]
    private ?string $reference = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Type('date')]
    #[Groups(['ticket_read','ticket_write'])]
    private ?\DateTimeImmutable $expireAt = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Groups(['tickets_read', 'ticket_read','ticket_write'])]
    private ?Event $event = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['tickets_read', 'ticket_read','ticket_write', 'read:Event'])]
    private ?User $holder = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getExpireAt(): ?\DateTimeImmutable
    {
        return $this->expireAt;
    }

    public function setExpireAt(\DateTimeImmutable $expireAt): self
    {
        $this->expireAt = $expireAt;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getHolder(): ?User
    {
        return $this->holder;
    }

    public function setHolder(?User $holder): self
    {
        $this->holder = $holder;

        return $this;
    }
}
