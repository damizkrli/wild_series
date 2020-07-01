<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Category;
use App\Entity\Program;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [ActorFixtures::class];
    }
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        $slugify = new Slugify();
        for ($i = 5; $i <= 1000; $i++) {
            $category = new Category();
            $category -> setName($faker -> word);
            $manager -> persist($category);
            $this -> addReference('category_' . $i, $category);
        }


        for ($k = 6; $k <= 1000; $k++) {
            $program = new Program();
            $program -> setTitle($faker -> sentence(4, true));
            $program -> setSynopsis($faker -> text(100));
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $program -> setCategory($this -> getReference('category_' . rand(0,1000)));
            $program -> setCountry($faker -> country);
            $program -> setYear($faker -> year($max = 'now'));
            $this -> addReference('program_' . $k, $program);
            for ($z = 0; $z < 5 ; $z++) {
                $program -> addActor($this -> getReference('actor_' . rand(0, 50)));
            }

            $manager -> persist($program);
        }


        $manager->flush();


    }
}
