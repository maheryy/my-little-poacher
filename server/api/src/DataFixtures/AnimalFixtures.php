<?php

namespace App\DataFixtures;

use App\Entity\Animal;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AnimalFixtures extends Fixture
{
    /**@var UserPasswordHasherInterface $passwordHash */
    private $userPasswordHash;



    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('en_US');

        $animaux = ["Lion", "Guépard", "Lémurien", "Kangourou", "Koala", "Ornithorynque", "Panda roux", "Wombat", "Paresseux", "Tamanoir", "Hippopotame", "Girafe", "Zèbre", "Chameau", "Éléphant", "Rhino", "Gorille", "Ours", "Chouette", "Paon"];
        $nomScientifique = ["Panthera leo", "Acinonyx jubatus", "Lemuriformes", "Macropus", "Phascolarctos cinereus", "Ornithorhynchus anatinus", "Ailurus fulgens", "Vombatidae", "Folivora", "Myrmecophaga tridactyla", "Hippopotamus amphibius", "Giraffa camelopardalis", "Equus quagga", "Camelus dromedarius", "Elephantidae", "Rhinocerotidae", "Gorilla", "Ursidae", "Strigiformes", "Pavo"];


        for($i = 0; $i < count($animaux); $i++){

            $date= $faker->dateTimeBetween('-1 years', '-3days');
            $date = DateTimeImmutable::createFromMutable($date);

            $object = new Animal();
            $object
                ->setName($animaux[$i])
                ->setScientificName($nomScientifique[$i])
                ->setCaptureDate($date)
                ->setLatitude($faker->randomFloat(5,-90, 90))
                ->setLongitude($faker->randomFloat(5,-180, 180))
                ->setCountry($faker->country);

            $manager->persist($object);
        }

        $manager->flush();

    }
}

