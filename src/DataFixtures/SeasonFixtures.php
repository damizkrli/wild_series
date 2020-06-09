<?php
namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('en_US');

        for ($i=0; $i<=5; $i++){
            $season = new Season();
            $season->setNumber($faker->numberBetween(1, 10));
            $season->setYear($faker->year);
            $season->setDescription($faker->text(255));
            $season->setProgram($this->getReference('program_' . $i));
            $this->addReference('season_' . $i, $season);
            $manager->persist($season);

        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}
