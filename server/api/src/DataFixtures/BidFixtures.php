<?php

namespace App\DataFixtures;

use App\Entity\Bid;
use App\Entity\Animal;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class BidFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $seller = $manager->getRepository(User::class)->findOneBy(['name' => 'client0']);
        for($i = 0; $i < 4; $i++){
            $animal = $manager->getRepository(Animal::class)->findOneBy(['name' => 'animal0']);
            $bid = new Bid();
            $bid
                ->setTitle('title'.$i)
                ->setSlug('slug'.$i)
                ->setDescription('description'.$i)
                ->setInitialPrice(100)
                ->setCurrentPrice(100)
                ->setStartAt(new \DateTimeImmutable())
                ->setEndAt(new \DateTimeImmutable())
                ->setStatus('1')
                ->setAnimal($animal)
                ->setSeller($seller)
            ;

            $manager->persist($bid);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            AnimalFixtures::class,
        ];
    }
}