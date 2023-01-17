<?php

namespace App\EventListener;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class LoginSuccessListener
{
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event): void
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof User) {
            return;
        }

        $data['user'] = [
            'id' => $user->getId(),
            'identifier' => $user->getIdentifier(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'status' => $user->getStatus()
        ];

        $event->setData($data);
    }
}