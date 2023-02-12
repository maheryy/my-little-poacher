<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\UserSellerRoleController;
use App\Repository\UserSellerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    normalizationContext: ['groups' => [ 'userSeller_read' ]],
    denormalizationContext: ['groups' => ['userSeller_write']],
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['userSeller_read', 'read:UserSellers']],
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'Only admins can list user sellers.'
        ),
        new Get(
            normalizationContext: ['groups' => ['userSeller_read', 'read:UserSeller']],
            security: 'is_granted("ROLE_ADMIN") or object.getSeller() == user',
            securityMessage: 'Only admins or the seller can view a user seller.'
        ),
        new Put(
            denormalizationContext: ['groups' => ['userSeller_write']],
            security: 'is_granted("ROLE_ADMIN") or object.getSeller() == user',
            securityMessage: 'Only admins or the seller can edit a user seller.'
        ),
        
        new Patch(
            denormalizationContext: ['groups' => ['userSeller_patch']],
            inputFormats: ['json' => ['application/json']],
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'Only admins can edit the role of users.'
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

    #[ORM\Column(length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Groups(['userSeller_read', 'userSeller_read', 'userSeller_write'])]
    private $address;

    #[ORM\Column(length: 500)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Groups(['userSeller_read', 'userSeller_read', 'userSeller_write'])]
    private string $description;

    #[ORM\Column(type: 'string',length:'25')]
    #[Groups(['userSeller_read','userSeller_write','userSeller_patch'])]
    private string $status = 'pending';

    #[ORM\OneToOne(inversedBy: 'userSeller', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['userSeller_read'])]
    private ?User $seller = null;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
    
}
