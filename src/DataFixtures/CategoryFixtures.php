<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $category1 = new Category();
        $category1->setName('Sport');
        $manager->persist($category1);
        $this->addReference('category1', $category1);

        $category2 = new Category();
        $category2->setName('Activités manuelles');
        $manager->persist($category2);
        $this->addReference('category2', $category2);

        $category3 = new Category();
        $category3->setName('Apprentissage');
        $manager->persist($category3);
        $this->addReference('category3', $category3);


        $manager->flush();
    }
}
