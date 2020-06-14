<?php
namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Program;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }
    const PROGRAMS = [
        'Walking Dead' => [
            'summary' => 'Sheriff Deputy Rick Grimes wakes up from a coma to learn the world is in ruins and must lead a group of survivors to stay alive.',
            'category' => 'category_4',
            'poster' => 'https://m.media-amazon.com/images/M/MV5BYTUwOTM3ZGUtMDZiNy00M2I3LWI1ZWEtYzhhNGMyZjI3MjBmXkEyXkFqcGdeQXVyMTkxNjUyNQ@@._V1_.jpg',
        ],
        'The Haunting Of Hill House' => [
            'summary' => 'Flashing between past and present, a fractured family confronts haunting memories of their old home and the terrifying events that drove them from it.',
            'category' => 'category_4',
            'poster' => 'https://m.media-amazon.com/images/M/MV5BMTU4NzA4MDEwNF5BMl5BanBnXkFtZTgwMTQxODYzNjM@._V1_.jpg',
        ],
        'American Horror Story' => [
            'summary' => 'An anthology series centering on different characters and locations.',
            'category' => 'category_4',
            'poster' => 'https://m.media-amazon.com/images/M/MV5BODZlYzc2ODYtYmQyZS00ZTM4LTk4ZDQtMTMyZDdhMDgzZTU0XkEyXkFqcGdeQXVyMzQ2MDI5NjU@._V1_.jpg',
        ],
        'Love Death And Robots' => [
            'summary' => 'A collection of animated short stories that span various genres including science fiction, fantasy, horror and comedy. ',
            'category' => 'category_4',
            'poster' => 'https://m.media-amazon.com/images/M/MV5BMTc1MjIyNDI3Nl5BMl5BanBnXkFtZTgwMjQ1OTI0NzM@._V1_.jpg',
        ],
        'Penny Dreadful' => [
            'summary' => 'Explorer Sir Malcolm Murray, American gunslinger Ethan Chandler, scientist Victor Frankenstein and medium Vanessa Ives unite to combat supernatural threats in Victorian London.',
            'category' => 'category_4',
            'poster' => 'https://m.media-amazon.com/images/M/MV5BMTQ0Mzg2NzcyNl5BMl5BanBnXkFtZTgwMDE1NzU2NDE@._V1_.jpg',
        ],
        'Fear The Walking Dead' => [
            'summary' => 'A Walking Dead spin-off, set in Los Angeles, following two families who must band together to survive the undead apocalypse.',
            'category' => 'category_4',
            'poster' => 'https://m.media-amazon.com/images/M/MV5BYWNmY2Y1NTgtYTExMS00NGUxLWIxYWQtMjU4MjNkZjZlZjQ3XkEyXkFqcGdeQXVyMzQ2MDI5NjU@._V1_.jpg',
        ],
    ];
    public function load(ObjectManager $manager)
    {
        $slugify = new Slugify();

        $i = 0;
        foreach (self::PROGRAMS as $title => $data) {
            $program = new Program();
            $program->setTitle($title);
            $program->setYear('2020');
            $program->setCountry('USA');
            $program->setPoster($data['poster']);
            $program->setSlug($slugify->generate($program->getTitle()));
            $program->setSynopsis($data['summary']);
            $this->addReference('program_' . $i, $program);
            $program->setCategory($this->getReference($data['category']));
            $manager->persist($program);
            $i++;
        }
        $manager->flush();
    }
}
