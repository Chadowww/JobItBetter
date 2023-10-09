<?php

namespace App\DataFixtures;

use AllowDynamicProperties;
use App\Entity\Resume;
use App\Repository\TechnologyRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

#[AllowDynamicProperties] class ResumeFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(TechnologyRepository $technologyRepository)
    {
        $this->technologyRepository = $technologyRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 40; $i++) {
            $resume = new Resume();
            $resume->setName($faker->jobTitle);
            $resume->setPath('assets/uploads/resumes/CVKevindavoust.pdf');

            for ($j = 0; $j < 7; $j++) {
                $technology = $this->getReference('technology_' . random_int(0, 49));
                $resume->addTechnology($technology);
                $manager->persist($technology);
            }
            $this->addReference('resume_' . $i, $resume);

            $manager->persist($resume);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TechnologiesFixtures::class,
        ];
    }
}
