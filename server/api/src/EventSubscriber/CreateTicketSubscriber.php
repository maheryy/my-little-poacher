<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Event;
use App\Entity\Ticket;
use App\Entity\User;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

final class CreateTicketSubscriber implements EventSubscriberInterface
{
    private $ticketRepository;
    private $security;
    private $entityManager;

    public function __construct(TicketRepository $ticketRepository, Security $security, EntityManagerInterface $entityManager)
    {
        $this->ticketRepository = $ticketRepository;
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['createTicket', EventPriorities::PRE_VALIDATE],
        ];
    }

    public function createTicket(ViewEvent $event): void
    {
        $ticket = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$ticket instanceof Ticket || Request::METHOD_POST !== $method) {
            return;
        }

        $user = $this->security->getUser();
        $event_entity = $ticket->getEvent();

        $this->checkUser($user);
        $this->checkEvent($user, $event_entity);

        do {
            $reference = $this->generateReference();
        } while ($this->ticketRepository->findOneBy(['reference' => $reference]));
        
        $ticket->setReference($reference);
        $ticket->setHolder($this->security->getUser());
        $ticket->setExpireAt(new \DateTimeImmutable('+1 hour'));

        $event->setControllerResult($ticket);
    }

    private function checkUser($user)
    {
        if(!$this->security->getUser()) {
            throw new \Exception('User is not connected');
        }
        if (!$user instanceof User) {
            throw new \Exception('User not found');
        }
        return;
    }

    private function checkEvent($user, $event_entity)
    {
        if (!$event_entity instanceof Event) {
            throw new \Exception('Event not found');
        }
        if ($event_entity->getTickets()->count() >= $event_entity->getCapacity()) {
            throw new \Exception('Event is full');
        }
        foreach($event_entity->getTickets() as $ticket) {
            if ($ticket->getHolder() === $user) {
                throw new \Exception('User already has a ticket for this event');
            }
        }
        return;
    }

    private function generateReference()
    {
        $reference = '';
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        for ($i = 0; $i < 10; $i++) {
            $reference .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $reference;
    }
}