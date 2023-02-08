<?php

namespace App\DataFixtures;

use App\Entity\Animal;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AnimalFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $captureDate = new \DateTimeImmutable();
        $captureDate->sub(new \DateInterval('P7D'));
        $captureDate->format('Y-m-d H:i:s');

        for($i = 0; $i < 5; $i++){
            $object = new Animal();
            $object
                ->setName('Marsupilami'.$i)
                ->setScientificName('Marsupilamus fantasii'.$i)
                ->setCaptureDate($captureDate)
                ->setLatitude('48.856614')
                ->setLongitude('2.352222')
                ->setCountry('ESGI Jungle'.$i);

            $manager->persist($object);
        }

        for($i = 0; $i < 5; $i++){
            $object = new Animal();
            $object
                ->setName('WinnieOurson'.$i)
                ->setScientificName('Oursum'.$i)
                ->setCaptureDate($captureDate)
                ->setLatitude('48.856614')
                ->setLongitude('2.352222')
                ->setCountry('ESGI Forest'.$i);

            $manager->persist($object);
        }

        for($i = 0; $i < 5; $i++){
            $object = new Animal();
            $object
                ->setName('PanthereRose'.$i)
                ->setScientificName('ChatMiaou'.$i)
                ->setCaptureDate($captureDate)
                ->setLatitude('48.856614')
                ->setLongitude('2.352222')
                ->setCountry('ESGI Savane'.$i);

            $manager->persist($object);
        }

        $manager->flush();
    }
}