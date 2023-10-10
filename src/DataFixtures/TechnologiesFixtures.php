<?php

namespace App\DataFixtures;

use App\Entity\Technology;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TechnologiesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $frontEndTechnologies = [
            "HTML", "CSS", "JavaScript", "jQuery", "Bootstrap", "Angular", "React", "Vue.js"
        ];
        $backEndTechnologies = [
            "PHP", "Laravel", "Symfony", "Python", "Django", "Ruby", "Ruby on Rails", "Node.js", "Express.js"
        ];
        $aplicationTechnologies = [
            "Java", "Spring Framework", ".NET", "C#", "ASP.NET", ".NET Core", "C++", "Go", "Rust"
        ];
        $otherWebTechnologies = [
             "Tailwind", "CodeIgniter", "WordPress", "Drupal", "Joomla", "Magento"
        ];
        $databaseTechnologies = [
            "MySQL", "PostgreSQL", "MongoDB", "Oracle", "SQL Server"
        ];
        $versionControlTechnologies = [
            "Git", "Docker", "Kubernetes"
        ];
        $cloudTechnologies = [
            "AWS (Amazon Web Services)", "Azure", "Google Cloud", "Heroku"
        ];
        $apiTechnologies = [
            "RESTful API", "GraphQL"
        ];

        $j = 1;
        foreach ($frontEndTechnologies as $technology) {
            $techno = new Technology();
            $techno->setName($technology);
            $techno->setCategory($this->getReference('category_1'));
            $this->addReference('technology_' . $j, $techno);
            $manager->persist($techno);
            $j++;
        }
        $j = 9;

        foreach ($backEndTechnologies as $technology) {
            $techno = new Technology();
            $techno->setName($technology);
            $techno->setCategory($this->getReference('category_2'));
            $this->addReference('technology_' . $j, $techno);
            $manager->persist($techno);
            $j++;
        }
        $j = 18;
        foreach ($otherWebTechnologies as $technology) {
            $techno = new Technology();
            $techno->setName($technology);
            $techno->setCategory($this->getReference('category_4'));
            $this->addReference('technology_' . $j, $techno);
            $manager->persist($techno);
            $j++;
        }
        $j = 24;
        foreach ($databaseTechnologies as $technology) {
            $techno = new Technology();
            $techno->setName($technology);
            $techno->setCategory($this->getReference('category_4'));
            $this->addReference('technology_' . $j, $techno);
            $manager->persist($techno);
            $j++;
        }
        $j = 29;
        foreach ($versionControlTechnologies as $technology) {
            $techno = new Technology();
            $techno->setName($technology);
            $techno->setCategory($this->getReference('category_5'));
            $this->addReference('technology_' . $j, $techno);
            $manager->persist($techno);
            $j++;
        }
        $j = 32;
        foreach ($cloudTechnologies as $technology) {
            $techno = new Technology();
            $techno->setName($technology);
            $techno->setCategory($this->getReference('category_6'));
            $this->addReference('technology_' . $j, $techno);
            $manager->persist($techno);
            $j++;
        }
        $j = 36;
        foreach ($apiTechnologies as $technology) {
            $techno = new Technology();
            $techno->setName($technology);
            $techno->setCategory($this->getReference('category_7'));
            $this->addReference('technology_' . $j, $techno);
            $manager->persist($techno);
            $j++;
        }
        $j = 38;
        foreach ($aplicationTechnologies as $technology) {
            $techno = new Technology();
            $techno->setName($technology);
            $techno->setCategory($this->getReference('category_3'));
            $this->addReference('technology_' . $j, $techno);
            $manager->persist($techno);
            $j++;
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
