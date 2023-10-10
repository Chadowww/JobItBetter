<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Job;

class JobFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $job = new Job();
        $job->setName('Développeur web');
        $this->addReference('job_0', $job);

        $manager->persist($job);

        $job = new Job();
        $job->setName('Développeur mobile');
        $this->addReference('job_1', $job);

        $manager->persist($job);

        $job = new Job();
        $job->setName('Scrum master');
        $this->addReference('job_2', $job);

        $manager->persist($job);

        $job = new Job();
        $job->setName('Chef de projet');
        $this->addReference('job_3', $job);

        $manager->persist($job);

        $job = new Job();
        $job->setName('Business developer');
        $this->addReference('job_4', $job);

        $manager->persist($job);

        $job = new Job();
        $job->setName('Data scientist');
        $this->addReference('job_5', $job);

        $manager->persist($job);

        $job = new Job();
        $job->setName('Data analyst');
        $this->addReference('job_6', $job);

        $manager->persist($job);

        $job = new Job();
        $job->setName('Chargé de recrutement');
        $this->addReference('job_7', $job);

        $manager->persist($job);

        $job = new Job();
        $job->setName('Responsable formation');
        $this->addReference('job_8', $job);

        $manager->persist($job);

        $job = new Job();
        $job->setName('Responsable RH');
        $this->addReference('job_9', $job);

        $manager->persist($job);

        $manager->flush();
    }
}
