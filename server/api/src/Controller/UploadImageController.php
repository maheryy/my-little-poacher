<?php

namespace App\Controller;

use App\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
final class UploadImageController extends AbstractController
{
    public function __invoke(Request $request): Image
    {
        throw new BadRequestHttpException('Calvin');
        $uploadedFile = $request->files->get('image');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('Missing image');
        }

        $image = new Image();
        $image->file = $uploadedFile;

        return $image;
    }
}