<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use App\Controller\UploadImageController;
use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ApiFilter(
    SearchFilter::class,
    properties: [
        'id' => 'exact',
])]
#[ApiResource(
    normalizationContext: ['groups' => ['image_read', 'images_read']],
    denormalizationContext: ['groups' => ['image_write']],
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['images_read', 'image_read']]
        ),
        new Get(
            normalizationContext: ['groups' => ['image_read']]
        ),
        new Put(
            denormalizationContext: ['groups' => ['image_write']],
            security: 'is_granted("ROLE_ADMIN")'
        ),
        new Delete(
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'Only admins can delete images.'
        ),
        new Post(
            uriTemplate: '/upload',
            name: 'upload',
            denormalizationContext: ['groups' => ['image_write']],
            controller: UploadImageController::class,
            input: 'App\Entity\Image',
            output: 'App\Entity\Image',
            status: 201,
            openapiContext: [
                'summary' => 'Upload image.',
                'requestBody' => [
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'imageFile' => [
                                        'type' => 'string',
                                        'format' => 'binary',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'responses' => [
                    '201' => [
                        'description' => 'The image has been uploaded.',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Image',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        )
    ]
)]
#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ApiProperty(types: ['https://schema.org/imageUrl'])]
    #[Groups(['image_read', 'images_read'])]
    #[ORM\Column]
    public ?string $imageUrl = null;
    
    #[Vich\UploadableField(mapping: "image", fileNameProperty: "filePath")]
    #[Assert\NotNull(groups: ['image_write'])]
    public ?File $file = null;

    #[ORM\Column(nullable: true)] 
    public ?string $filePath = null;
    
    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?Animal $animal = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $animal): self
    {
        $this->animal = $animal;

        return $this;
    }
}
