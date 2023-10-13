<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $categories = [
           "Technologies Web Front-End ", "Technologies Web Back-End ","Technologies de Développement
           d'Applications", "Frameworks et Outils Web", "Bases de données",
            "Outils de gestion de versions et déploiement", "Services Cloud", "Architecture d'API"
        ];
        $i = 1;
        foreach ($categories as $category) {
            $cat = new Category();
            $cat->setName($category);
            $this->addReference('category_' . $i, $cat);
            $manager->persist($cat);
            $i++;
        }

        $manager->flush();
    }
}
