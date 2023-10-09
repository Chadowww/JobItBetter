<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CompanyFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $companies = [
            'Société Générale',
            'Konbini',
            'Conforama',
            'Ikea',
            'Fnac',
            'Apple',
            'Cdiscount',
            'Microsoft',
            'Guarani',
            'Citadium'
        ];
        $logo = [
            'https://upload.wikimedia.org/wikipedia/fr/9/9b/Logo-societe-generale.png',
            'https://upload.wikimedia.org/wikipedia/fr/thumb/0/0a/Logo-konbini.svg/1200px-Logo-konbini.svg.png',
            'https://logos-marques.com/wp-content/uploads/2021/03/Conforama-Logo-1987.png',
            'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1b/Ikea-logo.png/1200px-Ikea-logo.png',
            'https://logos-marques.com/wp-content/uploads/2022/07/Fnac-Logo-1969.jpg',
            'https://assets.stickpng.com/images/580b57fcd9996e24bc43c516.png',
            'https://pbs.twimg.com/media/Eoyb0g8XYAI88bD?format=jpg&name=4096x4096',
            'https://news.microsoft.com/wp-content/uploads/prod/sites/113/2017/06/Microsoft-logo_rgb_c-gray.png',
            'https://www.guarani.fr/wp-content/themes/NakedWordpress/img/logoheader.png',
            'https://www.vtscan.fr/wp-content/uploads/2016/06/logo-citadium.png'
        ];

        for ($i = 0; $i < 10; $i++) {
            $company = new Company();
            $company->setName($companies[$i])
               ->setCity($faker->city)
               ->setPhone($faker->phoneNumber)
               ->setSiret('1443032370083' . $i)
               ->setLogo($logo[$i])
               ->setUser($this->getReference('user_company_' . $i));
            $this->addReference('company_' . $i, $company);
            $manager->persist($company);
        }

        $company = new Company();
        $company->setName('AsTuring')
            ->setCity('Libourne')
            ->setPhone($faker->phoneNumber)
            ->setSiret('1443032370083')
            ->setLogo('https://upload.wikimedia.org/wikipedia/fr/9/9b/Logo-societe-generale.png')
            ->setUser($this->getReference('user_42'));

        $manager->persist($company);

        $manager->flush();
    }


    public function getDependencies(): array
    {
        return [
            UserFixtures::class
        ];
    }
}
