<?php

namespace App\DataFixtures;

use App\Entity\Bid;
use App\Entity\User;
use App\Entity\UserBid;
use App\Enum\UserBidStatus;
use DateTimeImmutable;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserBidFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('en_US');

        $bids = $manager->getRepository(Bid::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();
        $bidders = [];
        foreach($users as $user){
            if(!in_array("ROLE_SELLER", $user->getRoles())){
                array_push($bidders, $user);
            }
        }
        
        foreach($bids as $bid){
            if($bid->getEndAt() < new DateTimeImmutable()){
                $userBid = new UserBid();
                $userBid
                    ->setBid($bid)
                    ->setBidder($faker->randomElement($bidders))
                    ->setStatus(UserBidStatus::DEFAULT);
                $manager->persist($userBid);
            } else {
                $userBid = new UserBid();
                $userBid
                    ->setBid($bid)
                    ->setBidder($faker->randomElement($bidders))
                    ->setStatus(UserBidStatus::DEFAULT);
                $manager->persist($userBid);
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            BidFixtures::class,
        ];
    }
}
