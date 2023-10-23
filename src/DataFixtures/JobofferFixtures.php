<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Joboffer;
use Faker\Factory;

class JobofferFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $titresOffresEmploi = [
            "Développeur PHP/Symfony",
            "Développeur Front-End React",
            "Tech Lead Symfony",
            "Ingénieur DevOps",
            "Analyste en cybersécurité",
            "Chef de projet IT",
            "Développeur Java Senior",
            "Designer d'expérience utilisateur (UX)",
            "Administrateur de bases de données",
            "Ingénieur en intelligence artificielle",
            "Architecte Cloud",
            "Analyste de données",
            "Gestionnaire de projet Agile/Scrum",
            "Ingénieur réseau",
            "Spécialiste en automatisation des tests",
            "Administrateur système Linux",
            "Analyste fonctionnel",
            "Ingénieur QA (Assurance qualité)",
            "Ingénieur en télécommunications",
            "Analyste en business intelligence"
        ];

        for ($i = 0; $i < 1000; $i++) {
            $joboffer = new Joboffer();
            $array = [0,1,2,3,4,5,6,7,8,9];
            $randJob = array_rand($array);
            $numberJob = $array[$randJob];
            $randCompany = array_rand($array);
            $numberCompany = $array[$randCompany];
            $randSalary = array_rand($array);
            $numberSalary = $array[$randSalary];
            $randContract = array_rand($array);
            $numberContract = $array[$randContract];
            $cities = [
                'Paris', 'Lyon', 'Marseille', 'Toulouse', 'Nice', 'Nantes', 'Strasbourg', 'Montpellier', 'Bordeaux'
            ];
            $randCity = array_rand($cities);
            $city = $cities[$randCity];

            $offer = "<h6>À propos de nous :</h6>
  <p>
    Nous sommes une entreprise dynamique et en pleine croissance spécialisée dans le développement de solutions web
    innovantes. Notre équipe passionnée est composée de professionnels talentueux qui repoussent constamment les limites
    de la technologie pour offrir des produits de qualité supérieure à nos clients. Nous cherchons actuellement un
    développeur Web PHP Symfony motivé et compétent pour rejoindre notre équipe.
  </p>

  <h6>Responsabilités :</h6>
  <ul>
    <li>Participer activement au cycle de développement de nos projets web en utilisant le framework PHP Symfony.</li>
    <li>Analyser les spécifications fonctionnelles et techniques,
    et participer à la conception de solutions adaptées.</li>
    <li>Développer, tester et déployer des fonctionnalités en respectant les normes de qualité et les délais fixés.</li>
    <li>Collaborer étroitement avec les membres de l'équipe, y compris les concepteurs UX/UI,
    les développeurs front-end et les testeurs.</li>
    <li>Résoudre les problèmes techniques et assurer la maintenance des applications existantes.</li>
    <li>Suivre les bonnes pratiques de développement, les normes de codage et les procédures internes.</li>
  </ul>

  <h6>Exigences :</h6>
  <ul>
    <li>Expérience professionnelle préalable en développement web PHP avec une maîtrise de Symfony.</li>
    <li>Solide connaissance des langages de programmation web tels que HTML, CSS et JavaScript.</li>
    <li>Expérience avec les bases de données relationnelles, notamment MySQL ou PostgreSQL.</li>
    <li>Compréhension des principes de développement orienté objet.</li>
    <li>Familiarité avec les outils de gestion de versions, tels que Git.</li>
    <li>Capacité à travailler en équipe, à communiquer efficacement
    et à s'adapter à un environnement en évolution rapide.</li>
    <li>Attitude proactive et souci du détail pour fournir des solutions de haute qualité.</li>
  </ul>

  <h6>Avantages :</h6>
  <ul>
    <li>Salaire compétitif correspondant à l'expérience et aux compétences.</li>
    <li>Environnement de travail collaboratif et innovant.</li>
    <li>Opportunités de développement professionnel et de formation continue.</li>
    <li>Projets stimulants et variés.</li>
    <li>Horaires flexibles et possibilité de télétravail.</li>
  </ul>";
            $joboffer
                ->setTitle($titresOffresEmploi[array_rand($titresOffresEmploi)])
                ->setDescription($offer)
                ->setCompany($this->getReference('company_' . $numberCompany))
                ->setJob($this->getReference('job_' . $numberJob))
                ->setSalaryMin($this->getReference('salary_' . $numberSalary)->getMin())
                ->setSalaryMax($this->getReference('salary_' . $numberSalary)->getMax())
                ->setCity($city)
                ->setContract($this->getReference('contract_' . $numberContract));

            $manager->persist($joboffer);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CompanyFixtures::class,
            ContractFixtures::class,
            JobFixtures::class,
            SalaryFixtures::class,
        ];
    }
}
