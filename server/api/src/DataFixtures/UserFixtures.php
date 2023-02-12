<?php


namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    /**@var UserPasswordHasherInterface $passwordHash */
    private $userPasswordHash;

    public function __construct(UserPasswordHasherInterface $userPasswordHash)
    {
        $this->userPasswordHash = $userPasswordHash;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('en_US');

        $object = (new User())
            ->setName($faker->firstName())
            ->setEmail('chadmlpuser@gmail.com')
            ->setRoles(['ROLE_USER'])
            ->setStatus('1')
            ->setIsVerified(true);

        $object->setPassword($this->userPasswordHash->hashPassword($object, 'password'));
        $manager->persist($object);

        for($i = 0; $i < 15; $i++) {
            $object = (new User())
                ->setName($faker->firstName())
                ->setEmail($faker->freeEmail())
                ->setRoles(['ROLE_USER'])
                ->setStatus('1')
                ->setIsVerified(true);

            $object->setPassword($this->userPasswordHash->hashPassword($object, 'password'));

            $manager->persist($object);
        }

        for($i = 0; $i < 15; $i++) {
            $object = (new User())
                ->setName($faker->firstName())
                ->setEmail($faker->freeEmail())
                ->setRoles(['ROLE_USER', 'ROLE_SELLER'])
                ->setStatus('1')
                ->setIsVerified(true);

            $object->setPassword($this->userPasswordHash->hashPassword($object, 'password'));

            $manager->persist($object);
        }

        $manager->flush();
    }
}