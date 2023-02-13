<?php

namespace App\DataFixtures;

use App\Entity\Bid;
use App\Entity\User;
use App\Entity\UserBid;
use DateTimeImmutable;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserBidFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $aker = \Faker\Factory::create('fr_FR');

        $bids = $manager->getRepository(Bid::class)->findAll();
        
        foreach($bids as $bid){
            
            $bidders = $manager->getRepository(User::class)->findAll();

            if($bid->getEndAt() < new DateTimeImmutable()){
                

        
                    $event = new UserBid();
                    $event
                        ->setBid($bid)
                        ->setBidder($aker->randomElement($bidders))
                        ->setStatus('0');
        
                    $manager->persist($event);
                    
            }
            else{

                    $event = new UserBid();
                    $event
                        ->setBid($bid)
                        ->setBidder($aker->randomElement($bidders))
                        ->setStatus('1');
        
                    $manager->persist($event);
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
