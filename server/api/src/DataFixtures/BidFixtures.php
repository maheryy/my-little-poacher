<?php

namespace App\DataFixtures;

use App\Entity\Bid;
use App\Entity\Animal;
use App\Entity\User;
use App\Enum\BidStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DateTimeImmutable;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class BidFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $bidDescription = [
            "Lion Auctions" => "Illegal sale of lion cubs.",
            "Siberian Tiger Auctions" => "Auctions of Siberian tigers.",
            "Lemur Auctions" => "Sale of lemurs under the table.",
            "Kangaroo Auctions" => "Sale of kangaroo.",
            "Koala Auctions" => "Purchase of adult koalas.",
            "Platypus Auctions" => "Sale of baby platypus.",
            "Red Panda Auctions" => "Black market for red pandas.",
            "Wombat Auctions" => "Purchase of combat wombats.",
            "Sloth Auctions" => "Illegal sale of pygmy sloths.",
            "Anteater Auctions" => "Sale of anteaters.",
            "Hippopotamus Auctions" => "Sale of adult hippopotamuses.",
            "Giraffe Auctions" => "Sale of breathtaking giraffes."
        ];

        $faker = Factory::create('en_US');

        $bidName = ["Lion Auctions", "Siberian Tiger Auctions", "Lemur Auctions", "Kangaroo Auctions", "Koala Auctions", "Platypus Auctions", "Red Panda Auctions", "Wombat Auctions", "Sloth Auctions", "Anteater Auctions", "Hippopotamus Auctions", "Giraffe Auctions"];

        $animals = ["Lion", "Cheetah", "Lemur", "Kangaroo", "Koala", "Platypus", "Red Panda", "Wombat", "Sloth", "Anteater", "Hippopotamus", "Giraffe", "Zebra", "Camel", "Elephant", "Rhino", "Gorilla", "Bear", "Owl", "Peacock"];

        $sellers = $manager->getRepository(User::class)->findAll();
        $bidOwner = [];
        for ($i = 0; $i < count($sellers); $i++) {
            if (in_array("ROLE_SELLER", $sellers[$i]->getRoles())) {
                array_push($bidOwner, $sellers[$i]);
            }
        }

        for ($i = 0; $i < 8; $i++) {
            $animal = $manager->getRepository(Animal::class)->findOneBy(['name' => $faker->randomElement($animals)]);
            $dateDebut = $faker->dateTimeBetween('-3 week', ' +3 week');
            $dateFin = $faker->dateTimeBetween('-1 week', '+4 week');

            while ($dateFin < $dateDebut) {
                $dateFin = $faker->dateTimeBetween('-1 week', '+4 week');
            }
            //peut pas empêcher un vendeur de faire monter enchère sur son propre animal pcq getSeller existe pas
            // while($faker->randomElement($seller) == $animal->getSeller()){
            //     $faker->randomElement($seller);
            // }

            $dateDebut = DateTimeImmutable::createFromMutable($dateDebut);
            $dateFin = DateTimeImmutable::createFromMutable($dateFin);


            $bid = new Bid();
            $bid
                ->setTitle($bidName[$i])
                ->setDescription($bidDescription[$bidName[$i]])
                ->setInitialPrice($faker->numberBetween(100, 200))
                ->setCurrentPrice($faker->numberBetween(201, 5000))
                ->setEndAt($dateFin)
                ->setStatus($dateFin < new DateTimeImmutable() ? BidStatus::DONE : BidStatus::PENDING)
                ->setAnimal($animal)
                ->setSeller($faker->randomElement($bidOwner));

            $manager->persist($bid);
        }

        for ($i = 8; $i < 12; $i++) {
            $animal = $manager->getRepository(Animal::class)->findOneBy(['name' => $faker->randomElement($animals)]);
            $dateDebut = $faker->dateTimeBetween('-3 week', ' +3 week');
            $dateFin = $faker->dateTimeBetween('-1 week', '+1 day');

            while ($dateFin < $dateDebut) {
                $dateFin = $faker->dateTimeBetween('-1 week', '+4 week');
            }
            //peut pas empêcher un vendeur de faire monter enchère sur son propre animal pcq getSeller existe pas
            // while($faker->randomElement($seller) == $animal->getSeller()){
            //     $faker->randomElement($seller);
            // }

            $dateDebut = DateTimeImmutable::createFromMutable($dateDebut);
            $dateFin = DateTimeImmutable::createFromMutable($dateFin);

            $bid = new Bid();
            $bid
                ->setTitle($bidName[$i])
                ->setDescription($bidDescription[$bidName[$i]])
                ->setInitialPrice($faker->numberBetween(100, 200))
                ->setCurrentPrice($faker->numberBetween(201, 5000))
                ->setEndAt($dateFin)
                ->setStatus($dateFin < new DateTimeImmutable() ? BidStatus::DONE : BidStatus::PENDING)
                ->setAnimal($animal)
                ->setSeller($faker->randomElement($bidOwner));

            $manager->persist($bid);
        }

        $bid = new Bid();
        $bid
            ->setTitle("Lion")
            ->setDescription("Illegal sale of lion cubs.")
            ->setInitialPrice($faker->numberBetween(100, 200))
            ->setCurrentPrice($faker->numberBetween(201, 5000))
            ->setEndAt(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('+1 week', '+2 week')))
            ->setStatus(BidStatus::PENDING)
            ->setAnimal($manager->getRepository(Animal::class)->findOneBy(['name' => 'Lion']))
            ->setSeller($manager->getRepository(User::class)->findOneBy(['email' => 'seller@gmail.com']));
        $manager->persist($bid);

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
