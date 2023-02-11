<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Ticket;
use App\Entity\Event;
use App\Enum\TicketStatus;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TicketFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $captureDate = new \DateTimeImmutable();
        $captureDate->sub(new \DateInterval('P7D'));
        $captureDate->format('Y-m-d H:i:s');

        $date = new \DateTimeImmutable();
        $date->add(new \DateInterval('P7D'));
        $date->format('Y-m-d H:i:s');
        $users = $manager->getRepository(User::class)->findAll();
        $events = $manager->getRepository(Event::class)->findAll();

        foreach ($users as $user) {
            if (!in_array("ROLE_USER", $user->getRoles())) {
                continue;
            }

            $eventIds = [];
            $nbTickets = mt_rand(1, 10);

            for ($i = 0; $i < $nbTickets; $i++) {
                $event = $events[mt_rand(0, count($events) - 1)];

                if (in_array($event->getId(), $eventIds)) {
                    $i--;
                    continue;
                }

                $eventIds[] = $event->getId();

                $status = [TicketStatus::PENDING, TicketStatus::CONFIRMED, TicketStatus::CANCELLED, TicketStatus::EXPIRED, TicketStatus::USED][mt_rand(0, 4)];
                $object = new Ticket();

                if (in_array($status, [TicketStatus::CONFIRMED, TicketStatus::USED])) {
                    $object->setPaidAt(new DateTimeImmutable());
                }

                $object
                    ->setReference($this->generateReference())
                    ->setExpireAt($date)
                    ->setEvent($event)
                    ->setHolder($user)
                    ->setStatus($status);

                $manager->persist($object);
            }

            $manager->flush();
        }
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

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            EventFixtures::class,
        ];
    }
}
