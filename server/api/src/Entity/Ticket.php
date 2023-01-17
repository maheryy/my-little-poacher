<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

#[ApiResource(mercure: true)]
#[ORM\Entity]
class Ticket
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    public string $reference = '';

    #[ORM\Column(type: 'integer')]
    public int $status = 0;

    #[ORM\Column(type: 'datetime')]
    public \DateTimeInterface $expireAt;

    #[ORM\ManyToOne(inversedBy: 'tickets', targetEntity: User::class)]
    public User $user;

    #[ORM\ManyToOne(inversedBy: 'tickets', targetEntity: Event::class)]
    public Event $event;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getExpireAt(): \DateTimeInterface
    {
        return $this->expireAt;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }
}