<?php
namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Bid;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DateTimeImmutable;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $comments = [ "Who dares to bid on it??",
        "I am the best",
        "I won't let go",
        "Give up!",
        "The strategy is to bid at the last minute",
        "Not sure there will be enough for everyone",
        "I can already picture it above the fireplace",
        "I'm ready to do anything to get it",
        "A beautiful creature in prospect",
        ];
        $faker = Factory::create('en_US');

        $dateTimeNow = new DateTimeImmutable();
        for ($j = 0; $j < 40; $j++) {
                $author = $manager->getRepository(User::class)->findAll();
                $bids = $manager->getRepository(Bid::class)->findAll();

                $bid = $faker->randomElement($bids);
                while ($bid->getStartAt() > $dateTimeNow) {
                    $bid = $faker->randomElement($bids);
                }

                
                $object = new Comment();
                $object
                    ->setContent($faker->randomElement($comments))
                    ->setBid($bid)
                    ->setAuthor($faker->randomElement($author))
                ;
                $manager->persist($object);
        
    
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

