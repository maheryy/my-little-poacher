<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Event;
use App\Entity\User;
use App\Repository\EventRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

final class EventSubscriber implements EventSubscriberInterface
{

    public function __construct(private EventRepository $eventRepository, private Security $security)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['createEvent', EventPriorities::PRE_VALIDATE],
        ];
    }

    public function createEvent(ViewEvent $event): void
    {
        $eventObj = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$eventObj instanceof Event || Request::METHOD_POST !== $method) {
            return;
        }

        $user = $this->security->getUser();

        if (!$this->security->getUser()) {
            throw new \Exception('User is not connected');
        }
        if (!$user instanceof User) {
            throw new \Exception('User not found');
        }

        $eventObj->setCreator($this->security->getUser());
    }
}
