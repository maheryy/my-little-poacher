<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints as Assert;


#[ApiResource (
    normalizationContext: ['groups' => ['events_read', 'event_read']],
    denormalizationContext: ['groups' => ['event_write']],
    operations : [
        new GetCollection(
            normalizationContext : ['groups' => ['event_read', 'events_read', 'read:Events']],
        ),
        new Get(
            normalizationContext : ['groups' => ['event_read', 'read:Event']]
        ),
        new Put(
            denormalizationContext: ['groups' => ['event_write']]
        ),
        new Post(
            denormalizationContext: ['groups' => ['event_write']]
        ),
    ]

)]
#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['events_read','event_write', 'read:Ticket'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Groups(['read:Ticket','events_read','event_read','event_write'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:Ticket','events_read','event_read','event_write'])]
    #[Assert\NotBlank]
    #[Assert\Length(min: 5)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['read:Ticket','events_read','event_read','event_write'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(['read:Ticket', 'event_read','event_write'])]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:Ticket', 'event_read','event_write'])]
    private ?string $address = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read:Ticket','event_read','event_write'])]
    private ?int $capacity = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read:Ticket','event_read','event_write'])]
    private ?int $registered_users = null;

    #[ORM\Column]
    #[Groups(['read:Ticket', 'event_read','event_write'])]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Groups(['event_read','event_write'])]
    private ?int $status = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:Ticket', 'events_read','event_read','event_write'])]
    private ?User $creator = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Ticket::class)]
    #[Groups(['event_read','event_write'])]
    private Collection $tickets;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(?int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getRegisteredUsers(): ?int
    {
        return $this->registered_users;
    }

    public function setRegisteredUsers(?int $registered_users): self
    {
        $this->registered_users = $registered_users;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

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
            $ticket->setEvent($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getEvent() === $this) {
                $ticket->setEvent(null);
            }
        }

        return $this;
    }
}
