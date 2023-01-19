<?php

namespace App\DataFixtures;

use App\Entity\Bid;
use App\Entity\Animal;
use App\Entity\User;
use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EventFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $debut= mt_rand(1674505348,1674764548);
        $dateDebut = new \DateTimeImmutable();
        $dateDebut = $dateDebut->setTimestamp($debut);  

        $creator = $manager->getRepository(User::class)->findOneBy(['name' => 'client0']);
        for($i = 0; $i < 25; $i++){
            
            $debut= mt_rand(1674505348,1674764548);
            $dateDebut = new \DateTimeImmutable();
            $dateDebut = $dateDebut->setTimestamp($debut);  

            $event = new Event();
            $event
                ->setName('Bakalaye vend des Marsupilami'.$i)
                ->setSlug('slug'.$i)
                ->setDescription('Ici ça bicrave du marsu'.$i)
                ->setPrice(100)
                ->setAddress($i.' rue de la Jugnle')
                ->setCapacity(16)
                ->setDate($dateDebut)
                ->setCreator($creator)
            ;

            $manager->persist($event);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
