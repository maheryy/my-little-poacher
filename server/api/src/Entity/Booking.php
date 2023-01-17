<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

#[ApiResource(mercure: true)]
#[ORM\Entity]
class Booking
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    public string $booker = '';

    #[ORM\Column(type: 'text')]
    public string $message = '';

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    public ?Room $room = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}