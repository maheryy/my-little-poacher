<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Controller\RegisterValidationController;
use App\Repository\UserRegisterTokenRepository;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource(
    denormalizationContext: ['groups' => ['userToken_write']],
    normalizationContext: ['groups' => ['userToken_read']],
    operations: [
        new Get(
            normalizationContext: ['groups' => ['userToken_read','userTokens_read']],
            openapiContext: [
                'summary' => 'Validate registration token',
                'tags' => ['Register'],
                'description' => 'Validate registration token',
            ],
            uriTemplate: '/register/{token}',
            controller: RegisterValidationController::class,
        ),
    ]
)]
#[ORM\Entity(repositoryClass: UserRegisterTokenRepository::class)]
class UserRegisterToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[ApiProperty(identifier: false)]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[ApiProperty(identifier: true)]
    private ?string $token = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\Column]
    private ?bool $active;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $account = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->active = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

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

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getAccount(): ?User
    {
        return $this->account;
    }

    public function setAccount(?User $account): self
    {
        $this->account = $account;

        return $this;
    }
}
