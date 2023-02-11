<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\UserSellerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    normalizationContext: ['groups' => [ 'userSeller_read']],
    denormalizationContext: ['groups' => ['userSeller_write']],
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['userSeller_read']],
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'Only admins can list user sellers.'
        ),
        new Get(
            normalizationContext: ['groups' => ['userSeller_read']],
            security: 'is_granted("ROLE_ADMIN") or object.getSeller() == user',
            securityMessage: 'Only admins or the seller can view a user seller.'
        ),
        new Put(
            denormalizationContext: ['groups' => ['userSeller_write']],
            security: 'is_granted("ROLE_ADMIN") or object.getSeller() == user',
            securityMessage: 'Only admins or the seller can edit a user seller.'
        ),
        new Post(
            denormalizationContext: ['groups' => ['userSeller_write']]
        ),
    ]
)]
#[ORM\Entity(repositoryClass: UserSellerRepository::class)]
class UserSeller
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['userSeller_read'])]
    private ?int $id = null;

    // #[ORM\Column(length: 255)]
    // #[Assert\NotNull]
    // #[Assert\NotBlank]
    // #[Assert\Type('string')]
    // #[Groups(['userSeller_read', 'userSeller_read', 'userSeller_write', 'read:User'])]
    // private ?string $address = null;

    #[ORM\OneToOne(inversedBy: 'userSeller', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['userSeller_read'])]
    private ?User $seller = null;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['userSeller_read','userSeller_write', 'read:User'])]
    private ?bool $pendingRequest = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    // public function getAddress(): ?string
    // {
    //     return $this->address;
    // }

    // public function setAddress(string $address): self
    // {
    //     $this->address = $address;

    //     return $this;
    // }

    public function getSeller(): ?User
    {
        return $this->seller;
    }

    public function setSeller(?User $seller): self
    {
        $this->seller = $seller;

        return $this;
    }

    public function getPendingRequest(): ?bool
    {
        return $this->pendingRequest;
    }

    public function setPendingRequest(bool $pendingRequest): self
    {
        $this->pendingRequest = $pendingRequest;

        return $this;
    }
    
}
