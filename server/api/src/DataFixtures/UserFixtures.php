<?php


namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\UserStatus;
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
            ->setEmail('user1@gmail.com')
            ->setRoles(['ROLE_USER'])
            ->setStatus(UserStatus::DEFAULT)
            ->setIsVerified(true);
        $object->setPassword($this->userPasswordHash->hashPassword($object, 'usermylittlepoacher2023'));
        $manager->persist($object);
        
        $object = (new User())
            ->setName($faker->firstName())
            ->setEmail('user2@gmail.com')
            ->setRoles(['ROLE_USER'])
            ->setIsVerified(true);
        $object->setPassword($this->userPasswordHash->hashPassword($object, 'usermylittlepoacher2023'));
        $manager->persist($object);
        
        $object = (new User())
            ->setName($faker->firstName())
            ->setEmail('seller@gmail.com')
            ->setRoles(['ROLE_USER', 'ROLE_SELLER'])
            ->setIsVerified(true);
        $object->setPassword($this->userPasswordHash->hashPassword($object, 'sellermylittlepoacher2023'));
        $manager->persist($object);

        $object = (new User())
            ->setName('admin')
            ->setEmail('admin@gmail.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setIsVerified(true);

        $object->setPassword($this->userPasswordHash->hashPassword($object, 'admin20232023admin'));
        $manager->persist($object);

        for($i = 0; $i < 5; $i++) {
            $object = (new User())
                ->setName($faker->firstName())
                ->setEmail($faker->freeEmail())
                ->setRoles(['ROLE_USER'])
                ->setIsVerified(true);

            $object->setPassword($this->userPasswordHash->hashPassword($object, 'usermylittlepoacher2023'));

            $manager->persist($object);
        }

        for($i = 0; $i < 5; $i++) {
            $object = (new User())
                ->setName($faker->firstName())
                ->setEmail($faker->freeEmail())
                ->setRoles(['ROLE_USER', 'ROLE_SELLER'])
                ->setIsVerified(true);

            $object->setPassword($this->userPasswordHash->hashPassword($object, 'sellermylittlepoacher2023'));

            $manager->persist($object);
        }

        $manager->flush();
    }
}