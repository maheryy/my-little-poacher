<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Enum\TicketStatus;
use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Security;

#[AsController]
class TicketVerificationController extends AbstractController
{
    public function __construct(private Security $security, private TicketRepository $ticketRepository)
    {
    }

    public function __invoke(string $reference): JsonResponse
    {
        $user = $this->security->getUser();

        $ticket = $this->ticketRepository->findOneBy(['reference' => $reference]);

        if (
            !$ticket
            || $ticket->getStatus() !== TicketStatus::CONFIRMED
            || $ticket->getEvent()->getCreator() !== $user
        ) {
            return new JsonResponse(['message' => 'Ticket non valide'], Response::HTTP_BAD_REQUEST);
        }


        $ticket->setStatus(TicketStatus::USED);
        $this->ticketRepository->save($ticket, true);

        return new JsonResponse(['success' => true, 'message' => 'Ticket validé'], Response::HTTP_OK);
    }
}
