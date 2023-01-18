<?php

namespace App\DataFixtures;

use App\Entity\Bid;
use App\Entity\Animal;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BidFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = $manager->getRepository(User::class)->findOneBy(['email' => 'client0@gmail.com']);
        for($i = 0; $i < 4; $i++){
            $animal = $manager->getRepository(Animal::class)->findOneBy(['name' => 'animal'.$i]);
            $bid = new Bid();
            $bid
                ->setTitle('title'.$i)
                ->setDescription('description'.$i)
                ->setInitialPrice(100)
                ->setStartAt(new \DateTimeImmutable())
                ->setEndAt(new \DateTimeImmutable())
                ->setStatus('1')
                ->setAnimal($animal)
                ->setSeller($user);

            $manager->persist($bid);
        }

    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            AnimalFixtures::class,
        ];
    }
}