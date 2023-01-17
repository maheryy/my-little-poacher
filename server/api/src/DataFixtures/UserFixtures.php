<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $object = new User();
        $object
            ->setIdentifier('admin')
            ->setEmail('admin@admin.com')
            ->setPassword($this->passwordHasher->hashPassword($object, 'password'))
            ->setRoles(['ROLE_ADMIN'])
            ->setStatus('1')
        ;
        $manager->persist($object);

        for($i = 0; $i < 4; $i++) {
            $object = new User();
            $object
                ->setIdentifier('client'.$i)
                ->setEmail('client'.$i.'@gmail.com')
                ->setPassword($this->passwordHasher->hashPassword($object, 'password'))
                ->setRoles(['ROLE_USER'])
                ->setStatus('1')
            ;
            $manager->persist($object);
        }

        $manager->flush();
    }
}