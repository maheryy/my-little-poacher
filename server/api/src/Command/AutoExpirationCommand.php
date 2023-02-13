<?php

namespace App\Command;

use App\Enum\BidStatus;
use App\Enum\EventStatus;
use App\Enum\TicketStatus;
use App\Repository\BidRepository;
use App\Repository\EventRepository;
use App\Repository\TicketRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:auto-expiration',
    description: 'Update expired app resources',
    hidden: false,
)]
class AutoExpirationCommand extends Command
{
    private $bidRepository;
    private $eventRepository;
    private $ticketRepository;

    public function __construct(BidRepository $bidRepository, EventRepository $eventRepository, TicketRepository $ticketRepository)
    {
        $this->bidRepository = $bidRepository;
        $this->eventRepository = $eventRepository;
        $this->ticketRepository = $ticketRepository;

        parent::__construct();
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $bids = $this->bidRepository->findAll();
        $events = $this->eventRepository->findAll();
        $tickets = $this->ticketRepository->findAll();

        foreach ($bids as $bid) {
            if ($bid->getEndAt() < new \DateTimeImmutable()) {
                $bid->setStatus(BidStatus::DONE);
                $this->bidRepository->save($bid);
            }
        }

        foreach ($events as $event) {
            if ($event->getDate() < new \DateTimeImmutable()) {
                $event->setStatus(EventStatus::DONE);
                $this->eventRepository->save($event);
            }
        }

        foreach ($tickets as $ticket) {
            if ($ticket->getExpireAt() < new \DateTimeImmutable()) {
                $ticket->setStatus(TicketStatus::EXPIRED);
                $this->ticketRepository->save($ticket);
            }
        }

        $output->writeln('Expiration successfull !');

        return Command::SUCCESS;
    }
}
