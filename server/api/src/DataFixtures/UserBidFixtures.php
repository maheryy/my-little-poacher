<?php

namespace App\DataFixtures;

use App\Entity\Bid;
use App\Entity\User;
use App\Entity\UserBid;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserBidFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        
        
        for($j = 0 ; $j < 14 ; $j++){
            $bid = $manager->getRepository(Bid::class)->findOneBy(['title' => 'Marsupilami au plus offrant '.$j]);
            if($bid->getEndAt() < new DateTimeImmutable()){
                for($i = 0; $i < 14; $i++){
                    $bidder = $manager->getRepository(User::class)->findOneBy(['name' => 'client'.$i]);
        
                    if($i>7){
                        $event = new UserBid();
                    $event
                        ->setBid($bid)
                        ->setBidder($bidder)
                        ->setStatus('-1')
                    ;
        
                    $manager->persist($event);
                    }
                    else{
                        $event = new UserBid();
                        $event
                            ->setBid($bid)
                            ->setBidder($bidder)
                            ->setStatus('1')
                        ;
            
                        $manager->persist($event);
                    }
                    
                }
            }
            elseif($bid->getEndAt() > new DateTimeImmutable() && $bid->getStartAt() < new DateTimeImmutable()){
                for($i = 0; $i < 14; $i++){
                    $bidder = $manager->getRepository(User::class)->findOneBy(['name' => 'client'.$i]);
        
                    $event = new UserBid();
                    $event
                        ->setBid($bid)
                        ->setBidder($bidder)
                        ->setStatus('0');
        
                    $manager->persist($event);
                    
            }
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
