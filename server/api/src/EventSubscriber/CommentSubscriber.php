<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Comment;
use App\Entity\User;
use App\Repository\CommentRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

final class CommentSubscriber implements EventSubscriberInterface
{

    public function __construct(private CommentRepository $commentRepository, private Security $security)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['createComment', EventPriorities::PRE_VALIDATE],
        ];
    }

    public function createComment(ViewEvent $event): void
    {
        $comment = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$comment instanceof Comment || Request::METHOD_POST !== $method) {
            return;
        }

        $user = $this->security->getUser();

        if (!$this->security->getUser()) {
            throw new \Exception('User is not connected');
        }
        if (!$user instanceof User) {
            throw new \Exception('User not found');
        }

        $comment->setAuthor($this->security->getUser());
    }
}
