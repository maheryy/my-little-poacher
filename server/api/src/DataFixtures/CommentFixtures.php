<?php
namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Bid;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        for ($j = 0; $j < 15; $j++) {
            for($i = 0; $i < 15; $i++) {
                $author = $manager->getRepository(User::class)->findOneBy(['name' => ('client'.$i)]);
                $bid = $manager->getRepository(Bid::class)->findOneBy(['title' => ('Marsupilami au plus offrant '.$j)]);
                
                $object = new Comment();
                $object
                    ->setContent('Je veux lui faire '.$i.' bisous ! OUBAH OUBAH')
                    ->setBid($bid)
                    ->setAuthor($author)
                ;
                $manager->persist($object);
            }
    
            for($i = 0; $i < 15; $i++) {
                $author = $manager->getRepository(User::class)->findOneBy(['name' => ('client'.$i)]);
                $bid = $manager->getRepository(Bid::class)->findOneBy(['title' => ('WinnieOurson pour le plus gourmand '.$j)]);
                $object = new Comment();
                $object
                    ->setContent('Je veux lui mettre au moins '.$i.' giffles !')
                    ->setBid($bid)
                    ->setAuthor($author)
                ;
                $manager->persist($object);
            }
    
            for($i = 0; $i < 15; $i++) {
                $author = $manager->getRepository(User::class)->findOneBy(['name' => ('client'.$i)]);
                $bid = $manager->getRepository(Bid::class)->findOneBy(['title' => ('PanthereRose vous connaissez la chanson '.$j)]);
                $object = new Comment();
                $object
                    ->setContent('Il m\en manque justement '.$i)
                    ->setBid($bid)
                    ->setAuthor($author)
                ;
                $manager->persist($object);
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

