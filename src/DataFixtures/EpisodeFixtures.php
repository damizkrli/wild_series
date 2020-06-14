<?php
namespace App\DataFixtures;

use App\Entity\Episode;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('en_US');
        $slugify = new Slugify();

        for ($i=0; $i<=5; $i++){
            $episode = new Episode();
            $episode->setNumber($faker->numberBetween(1, 10));
            $episode->setTitle($faker->text(30));
            $episode->setSynopsis($faker->text(255));
            $episode->setSeason($this->getReference('season_' . $i));
            $episode->setSlug($slugify->generate($episode->getTitle()));
            $this->addReference('episode_' . $i, $episode);
            $manager->persist($episode);

        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }
}
