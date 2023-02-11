<?php

namespace App\Controller;

use App\Entity\UserRegisterToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class RegisterValidationController extends AbstractController
{
    private const ENTITY = UserRegisterToken::class;
    private const VALIDATE_TIME = 3600;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        
    }

    public function __invoke(Request $request)
    {
        $entity = $request->attributes->get('data');

        if(get_class($entity) === self::ENTITY) {
            if(!$entity->getActive()) {
                return $this->json(['message' => 'Token already used'], 400);
            }

            if($entity->getCreatedAt()->getTimestamp() + self::VALIDATE_TIME < time()) {
                $this->entityManager->remove($entity);
                $this->entityManager->flush();
                return $this->json(['message' => 'Token expired'], 400);
            }

            $entity->setActive(false);
            $entity->getAccount()->setIsVerified(true);
            $this->entityManager->flush();

            return $this->json(['message' => 'Account verified']);
        }

        return $this->json(['message' => 'Invalid token'], 400);
    }
}