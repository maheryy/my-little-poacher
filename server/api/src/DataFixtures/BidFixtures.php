<?php

namespace App\DataFixtures;

use App\Entity\Bid;
use App\Entity\Animal;
use App\Entity\User;
use App\Repository\BidRepository;
use App\Repository\AnimalRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BidFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user1 = $manager->getRepository(User::class)->find(2);

        for($i = 0; $i < 4; $i++){
            $animal = new Animal();
            $animal
                ->setName('animal'.$i)
                ->setScientificName('scientificName'.$i)
                ->setCaptureDate(new \DateTimeImmutable())
                ->setCountry('country'.$i);

            $manager->persist($animal);

            $bid = new Bid();
            $bid
                ->setTitle('title'.$i)
                ->setDescription('description'.$i)
                ->setInitialPrice(100)
                ->setStartAt(new \DateTimeImmutable())
                ->setEndAt(new \DateTimeImmutable())
                ->setStatus('1')
                ->setAnimal($animal)
                ->setSeller($user1);

            $manager->persist($bid);
        }

    }
}