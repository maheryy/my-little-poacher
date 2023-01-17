<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

#[ApiResource(mercure: true)]
#[ORM\Entity]
class Event
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private ?int $id = null;
    
    #[ORM\Column(type: 'string', length: 255)]
    public string $name = '';

    #[ORM\Column(type: 'string', length: 255)]
    public string $slug = '';

    #[ORM\Column(type: 'string', length: 255)]
    public string $description = '';

    #[ORM\Column(type: 'string', length: 255)]
    public string $address = '';

    #[ORM\Column(type: 'integer')]
    public int $capacity = 0;

    #[ORM\Column(type: 'integer')]
    public int $registered_users = 0;

    #[ORM\Column(type: 'integer')]
    public int $status = 0;

    #[ORM\Column(type: 'datetime')]
    public \DateTimeInterface $date;

    #[ORM\ManyToOne(inversedBy:'events')]
    public ?User $creator = null;
    

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Ticket::class)]
    public ArrayCollection $tickets;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getCapacity(): int
    {
        return $this->capacity;
    }

    public function getRegisteredUsers(): int
    {
        return $this->registered_users;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function getCreator(): User
    {
        return $this->creator;
    }

    public function getTickets(): ArrayCollection
    {
        return $this->tickets;
    }
}