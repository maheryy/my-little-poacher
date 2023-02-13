<?php

namespace App\DataFixtures;

use App\Entity\Bid;
use App\Entity\Animal;
use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EventFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('en_US');



        $eventDescription = [
            "Secret Lion Sale" => "Illegal sale of lion cubs.",
            "Secret Siberian Tiger Sale" => "Secret sale of Siberian tigers.",
            "Secret Lemur Sale" => "Sale of lemurs under the table.",
            "Secret Kangaroo Sale" => "Sale of kangaroo.",
            "Secret Koala Sale" => "Purchase of adult koalas.",
            "Secret Platypus Sale" => "Sale of baby platypus.",
            "Secret Red Panda Sale" => "Black market for red pandas.",
            "Secret Wombat Sale" => "Purchase of combat wombats.",
            "Secret Sloth Sale" => "Illegal sale of pygmy sloths.",
            "Secret Anteater Sale" => "Sale of anteaters.",
            "Secret Hippopotamus Sale" => "Sale of adult hippopotamuses.",
            "Secret Giraffe Sale" => "Sale of breathtaking giraffes."
            ];

            $eventName = ["Secret Lion Sale", "Secret Siberian Tiger Sale", "Secret Lemur Sale", "Secret Kangaroo Sale", "Secret Koala Sale", "Secret Platypus Sale", "Secret Red Panda Sale", "Secret Wombat Sale", "Secret Sloth Sale", "Secret Anteater Sale", "Secret Hippopotamus Sale", "Secret Giraffe Sale"];
        
 

        $creators = $manager->getRepository(User::class)->findAll();
        $seller =[];
        for ($i = 0; $i < count($creators); $i++) {
            if (in_array("ROLE_SELLER", $creators[$i]->getRoles())) {
                array_push($seller, $creators[$i]);
            }
        }

        for($i = 0; $i < count($eventName); $i++){
            
            $dateDebut = $faker->dateTimeBetween('-2 week', '+4 week');  

            $dateDebut = DateTimeImmutable::createFromMutable($dateDebut);

            
                $event = new Event();
            $event
                ->setAddress("24 boulevard du double rotor, 75013 Paris")
                ->setPrice($faker->numberBetween(200, 3000))
                ->setDescription($eventDescription[$eventName[$i]])
                ->setName($eventName[$i])
                ->setCapacity(16)
                ->setDate($dateDebut)
                ->setCreator($faker->randomElement($seller))
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
