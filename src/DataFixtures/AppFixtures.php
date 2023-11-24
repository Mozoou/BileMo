<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 16; $i++) { 
            $product = new Product();
            $product->setName('Product'. $i);
            $product->setPrice(rand(1, 9));
            $product->setDescription('This is a product description !');

            $manager->persist($product);
        }


        $manager->flush();
    }
}
