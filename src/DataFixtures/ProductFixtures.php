<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 20; $i++){
            $product = new Product();

            $product->setName($faker->sentence(4,  true))
                ->setType('Product')
                ->setDescription($faker->paragraph(3, true))
                ->setCode($faker->ean8)
                ->setReference($faker->ean8)
                ->setStock($faker->randomDigit)
                ->setProvider($faker->company)
            ;

            $manager->persist($product);

        }

        $manager->flush();
    }
}
