<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Patch;
use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['comments_read','comment_read']],
    denormalizationContext: ['groups' => ['comment_write']],
    paginationItemsPerPage: 20,
    paginationMaximumItemsPerPage: 20,
    paginationClientEnabled: true,
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['comments_read','comment_read']]
        ),
        new Get(
            normalizationContext: ['groups' => ['comment_read']]
        ),
        new Put(
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'Only admins can edit comments.',
            denormalizationContext: ['groups' => ['comment_write']]
        ),
        new Post(
            denormalizationContext: ['groups' => ['comment_write']]
        ),
        new Delete(
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'Only admins can delete comments.'
        ),
        new Patch(
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'Only admins can patch comments.',
            denormalizationContext: ['groups' => ['comment_patch']]
        )
    ]
)]
#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['read:Bid', 'comment_read', 'comments_read', 'comment_write', 'comment_patch'])]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['comment_read', 'comments_read'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['comment_read', 'comments_read', 'comment_write'])]
    private ?Bid $bid = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:Bid', 'comment_read', 'comments_read', 'comment_write'])]
    private ?User $author = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getBid(): ?Bid
    {
        return $this->bid;
    }

    public function setBid(?Bid $bid): self
    {
        $this->bid = $bid;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
