<?php

namespace App\DataFixtures;

use App\Entity\Bid;
use App\Entity\User;
use App\Entity\BidLog;
use App\Entity\UserBid;
use DateTimeImmutable;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class BidLogFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('en_US');

        $bids = $manager->getRepository(Bid::class)->findAll();

        foreach($bids as $bid) {
            $userBid = $manager->getRepository(UserBid::class)->findOneBy(['bid' => $bid]);
            $bidLog = new BidLog();
            $bidLog
                ->setPrice($bid->getInitialPrice() + 50)
                ->setBid($bid)
                ->setBidder($userBid->getBidder());
            $manager->persist($bidLog);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            BidFixtures::class,
            UserBidFixtures::class
        ];
    }
}