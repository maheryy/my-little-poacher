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

        $eventInfo = array (

        );

        $eventDescription = [
            "Vente secrète de Lion" =>  "Vente illégale de lionceaux.",
         "Vente secrète de Tigre de siberie" => "Vente secrète de tigres de Siberie.",
          "Vente secrète de Lémurien" => "Vente de Lémuriens sous le manteau.",
          "Vente secrète de Kangourou" => "Vente de Kangourou.",
           "Vente secrète de Koala" => "Achat de Koala adultes.",
           "Vente secrète de Ornithorynque" => "Vente de bébés ornithorynque.",
           "Vente secrète de Panda roux" => "Marché noir de panda roux.",
           "Vente secrète de Wombat" => "Achat de wombat de combat.",
           "Vente secrète de Paresseux" => "Vente illégale de paresseux nains.",
           "Vente secrète de Tamanoir" => "Vente de tamanoir.", 
           "Vente secrète de Hippopotame" => "Vente d'Hippopotames adultes", 
           "Vente secrète de Girafe" => "Vente de girafes à couper le souffle."
        ];

        $eventName = ["Vente secrète de Lion", "Vente secrète de Tigre de siberie", "Vente secrète de Lémurien", "Vente secrète de Kangourou", "Vente secrète de Koala", "Vente secrète de Ornithorynque", "Vente secrète de Panda roux", "Vente secrète de Wombat", "Vente secrète de Paresseux", "Vente secrète de Tamanoir", "Vente secrète de Hippopotame", "Vente secrète de Girafe"];

        
 

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
                ->setName($eventName[$i])
                ->setSlug(str_replace(' ','-',$eventName[$i]))
                ->setDescription($eventDescription[$eventName[$i]])
                ->setPrice($faker->numberBetween(200, 3000))
                ->setAddress("24 boulevard du double rotor, 75013 Paris")
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
