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

        $animals = ["Lion", "Siberian Tiger", "Lemur", "Kangaroo", "Koala", "Platypus", "Red Panda", "Wombat", "Sloth", "Anteater", "Hippopotamus", "Giraffe"];
        $nomScientifique = ["Panthera leo", "Acinonyx jubatus", "Lemuriformes", "Macropus", "Phascolarctos cinereus", "Ornithorhynchus anatinus", "Ailurus fulgens", "Vombatidae", "Folivora", "Myrmecophaga tridactyla", "Hippopotamus amphibius", "Giraffa camelopardalis", "Equus quagga", "Camelus dromedarius", "Elephantidae", "Rhinocerotidae", "Gorilla", "Ursidae", "Strigiformes", "Pavo"];


        for($i = 0; $i < count($animals); $i++){

            $date= $faker->dateTimeBetween('-1 years', '-3days');
            $date = DateTimeImmutable::createFromMutable($date);

            $object = new Animal();
            $object
                ->setName($animals[$i])
                ->setScientificName($nomScientifique[$i])
                ->setCaptureDate($date)
                ->setCountry($faker->country);

            $manager->persist($object);
        }

        $manager->flush();

    }
}

