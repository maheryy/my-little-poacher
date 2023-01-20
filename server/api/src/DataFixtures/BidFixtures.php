<?php

namespace App\DataFixtures;

use App\Entity\Bid;
use App\Entity\Animal;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class BidFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $seller = $manager->getRepository(User::class)->findOneBy(['name' => 'vendeur3']);
        $animal = $manager->getRepository(Animal::class)->findOneBy(['name' => 'Marsupilami1']);
        for($i = 0; $i < 15; $i++){

            $fin= mt_rand(1675595044,1678014244); // 5 février à 5 mars
            $dateFin = new \DateTimeImmutable();
            $dateFin = $dateFin->setTimestamp($fin);

            $debut= mt_rand(1674126244,1675595044);//19 janv à 5 février
            $dateDebut = new \DateTimeImmutable();
            if($i<5){
                $dateDebut = $dateDebut->setTimestamp($debut)->sub(new \DateInterval('P40D'));
                $dateFin = $dateFin->setTimestamp($fin)->sub(new \DateInterval('P40D'));

            }else{
                $dateDebut = $dateDebut->setTimestamp($debut)->sub(new \DateInterval('P8D'));
                $dateFin = $dateFin->setTimestamp($fin)->add(new \DateInterval('P5D'));
            }
            
            $bid = new Bid();
            $bid
                ->setTitle('Marsupilami au plus offrant '.$i)
                ->setSlug('marsu-slug'.$i)
                ->setDescription('Marsupilami à vendre parlez pas chinois num-'.$i)
                ->setInitialPrice(100)
                ->setCurrentPrice(100)
                ->setStartAt($dateDebut)
                ->setEndAt($dateFin)
                ->setStatus('1')
                ->setAnimal($animal)
                ->setSeller($seller)
            ;

            $manager->persist($bid);
        }

        $seller = $manager->getRepository(User::class)->findOneBy(['name' => 'vendeur2']);
        $animal = $manager->getRepository(Animal::class)->findOneBy(['name' => 'WinnieOurson1']);
        for($i = 0; $i < 15; $i++){

            
            $fin= mt_rand(1675595044,1678014244);
            $dateFin = new \DateTimeImmutable();
            $dateFin = $dateFin->setTimestamp($fin);

            $debut= mt_rand(1674126244,1675595044);
            $dateDebut = new \DateTimeImmutable();
            if($i<5){
                $dateDebut = $dateDebut->setTimestamp($debut)->sub(new \DateInterval('P40D'));
                $dateFin = $dateFin->setTimestamp($fin)->sub(new \DateInterval('P40D'));

            }else{
                $dateDebut = $dateDebut->setTimestamp($debut);
                $dateFin = $dateFin->setTimestamp($fin);
            }

            $bid = new Bid();
            $bid
                ->setTitle('WinnieOurson pour le plus gourmand '.$i)
                ->setSlug('winnie-slug'.$i)
                ->setDescription('Winnizi ourson gourmand chantant num-'.$i)
                ->setInitialPrice(100)
                ->setCurrentPrice(100)
                ->setStartAt($dateDebut)
                ->setEndAt($dateFin)
                ->setStatus('1')
                ->setAnimal($animal)
                ->setSeller($seller)
            ;

            $manager->persist($bid);
        }

        $seller = $manager->getRepository(User::class)->findOneBy(['name' => 'vendeur1']);
        $animal = $manager->getRepository(Animal::class)->findOneBy(['name' => 'PanthereRose1']);
        for($i = 0; $i < 15; $i++){

            $fin= mt_rand(1675595044,1678014244);
            $dateFin = new \DateTimeImmutable();
            $dateFin = $dateFin->setTimestamp($fin);

            $debut= mt_rand(1674126244,1675595044);
            $dateDebut = new \DateTimeImmutable();
            if($i<5){
                $dateDebut = $dateDebut->setTimestamp($debut)->sub(new \DateInterval('P40D'));
                $dateFin = $dateFin->setTimestamp($fin)->sub(new \DateInterval('P40D'));

            }else{
                $dateDebut = $dateDebut->setTimestamp($debut);
                $dateFin = $dateFin->setTimestamp($fin);
            }

            $bid = new Bid();
            $bid
                ->setTitle('PanthereRose vous connaissez la chanson '.$i)
                ->setSlug('panthere-rose-slug'.$i)
                ->setDescription('Panthere ROSE discrète et malicieuse num-'.$i)
                ->setInitialPrice(100)
                ->setCurrentPrice(100)
                ->setStartAt($dateDebut)
                ->setEndAt($dateFin)
                ->setStatus('1')
                ->setAnimal($animal)
                ->setSeller($seller)
            ;

            $manager->persist($bid);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            AnimalFixtures::class,
        ];
    }
}
