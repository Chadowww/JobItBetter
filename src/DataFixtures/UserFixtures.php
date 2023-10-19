<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Faker\Factory;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private UserPasswordHasherInterface $hasher;

    /**
     * @param UserPasswordHasherInterface $hasher
     */
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $company = new User();
            $company->setFirstname($faker->firstName());
            $company->setLastname($faker->lastName());
            $company->setEmail('company' . $i . '@jobitbetter.com');
            $company->setPassword($this->hasher->hashPassword($company, 'company'));
            $company->setRoles(['ROLE_COMPANY']);
            $this->addReference('user_company_' . $i, $company);
            //pour le test a effacer pour la démo
            $company->setIsVerified(true);
            $manager->persist($company);
        }

        for ($i = 0; $i < 40; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setEmail('user' . $i . '@jobitbetter.com');
            $user->setPassword($this->hasher->hashPassword($user, 'user'));
            $user->setRoles(['ROLE_CANDIDATE']);
            $this->addReference('user_' . $i, $user);
            $user->addResume($this->getReference('resume_' . $i));
            $user->addResume($this->getReference('resume_' . $i));
            //pour le test a effacer pour la démo
            $user->setIsVerified(true);
            $manager->persist($user);
        }

        $admin = new User();
        $admin->setFirstname($faker->firstName());
        $admin->setLastname($faker->lastName());
        $admin->setEmail('admin@jobitbetter.com');
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin'));
        $admin->setRoles(['ROLE_ADMIN']);
        //pour le test a effacer pour la démo
        $admin->setIsVerified(true);
        $manager->persist($admin);

        $chadoUser = new User();
        $chadoUser->setFirstname('Alexadre');
        $chadoUser->setLastname('Salé');
        $chadoUser->setEmail('a.sale@hotmail.fr');
        $chadoUser->setPassword($this->hasher->hashPassword($chadoUser, 'Fw7jzpdr7!'));
        $chadoUser->setRoles(['ROLE_CANDIDATE']);
        $chadoUser->setIsVerified(true);
        $manager->persist($chadoUser);

        $chadoCompany = new User();
        $chadoCompany->setFirstname('Alexadre');
        $chadoCompany->setLastname('Salé');
        $chadoCompany->setEmail('alexandresale.dev@outlook.com');
        $chadoCompany->setPassword($this->hasher->hashPassword($chadoCompany, 'Fw7jzpdr7!'));
        $chadoCompany->setRoles(['ROLE_COMPANY']);
        $this->addReference('user_42', $chadoCompany);
        $chadoCompany->setIsVerified(true);
        $manager->persist($chadoCompany);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ResumeFixtures::class,
        ];
    }
}
