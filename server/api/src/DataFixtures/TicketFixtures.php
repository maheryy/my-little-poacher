<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Ticket;
use App\Entity\Event;
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

        

        for($j = 0; $j <25; $j++){
        $nbTicketPaid = mt_rand(0,15);
        $event = $manager->getRepository(Event::class)->findOneBy(['name' => 'Bakalaye vend des Marsupilami'.$j]);      
        for($i = 0; $i < $nbTicketPaid; $i++){
            $nbTicketPaid = mt_rand(0,15);

            $holder = $manager->getRepository(User::class)->findOneBy(['name' => 'client'.$i]);
    
                $uuid = $this->generateUuid();
                $object = new Ticket();
                $object
                    ->setReference($uuid)
                    ->setExpireAt( $date )
                    ->setEvent($event)
                    ->setHolder($holder)
                    ;
                $manager->persist($object);
            
            }
            $manager->flush();
        }
        }

        

    public function generateUuid(){
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            EventFixtures::class,
        ];
    }
}