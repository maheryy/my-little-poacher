<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Ticket;
use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Security;

#[AsController]
class BuyTicketController extends AbstractController
{
    private $ticketRepository;
    private $security;

    public function __construct(Security $security, TicketRepository $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
        $this->security = $security;
    }

    public function __invoke(Event $data, Request $request): Event
    {
        if(!$this->security->getUser()) {
            throw new \Exception('User is not connected');
        }
        if($data->getTickets()->count() >= $data->getCapacity()) {
            throw new \Exception('Event is full');
        }
        foreach($data->getTickets() as $ticket) {
            if($ticket->getHolder() === $this->security->getUser()) {
                throw new \Exception('User already bought a ticket');
            }
        }
        $ticket = new Ticket();
        do {
            $reference = strtoupper(substr(uniqid(sha1(time())),0,6));
        } while($this->ticketRepository->findOneBy(['reference' => $reference]));
        $ticket->setReference($reference);
        $ticket->setHolder($this->security->getUser());
        $ticket->setEvent($data);
        $ticket->setExpireAt(new \DateTimeImmutable('+1 hour'));
        $this->ticketRepository->save($ticket);
        return $data;
    }
}