<?php

namespace App\DataFixtures;

use App\Entity\Technology;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TechnologiesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $technologies = [
            "HTML", "CSS", "JavaScript", "jQuery", "Bootstrap", "Angular", "React", "Vue.js", "PHP",
            "Laravel", "Symfony", "Python", "Django", "Ruby", "Ruby on Rails", "Node.js", "Express.js", "Java",
            "Spring Framework", ".NET", "C#", "ASP.NET", "Python", "Flask", "Ruby", "Ruby on Rails", "ASP.NET",
            "C#", ".NET Core", "Tailwind", "Laravel", "CodeIgniter", "WordPress", "Drupal", "Joomla", "Magento",
            "MySQL", "PostgreSQL", "MongoDB", "Oracle", "SQL Server", "Git", "Docker", "Kubernetes",
            "AWS (Amazon Web Services)", "Azure", "Google Cloud", "Heroku", "RESTful API", "GraphQL"
        ];
        $i = 0;

        foreach ($technologies as $technology) {
            $techno = new Technology();
            $techno->setName($technology);
            $manager->persist((new Technology())->setName($technology));
            $this->addReference('technology_' . $i, $techno);
            $i++;
        }

        $manager->flush();
    }
}
