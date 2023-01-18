<?php

namespace App\DataFixtures;

use App\Entity\Animal;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AnimalFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 0; $i < 4; $i++){
            $object = new Animal();
            $object
                ->setName('animal'.$i)
                ->setScientificName('scientificName'.$i)
                ->setCaptureDate(new \DateTimeImmutable())
                ->setCountry('country'.$i);

            $manager->persist($object);
        }

        $manager->flush();
    }
}