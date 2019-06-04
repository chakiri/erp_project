<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\TypeProduct;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 3; $i++){
            $typeProduct = new TypeProduct();

            $types = ['Game', 'Mobile', 'Accessory'];

            $typeProduct->setName($types[$i]);

            for ($j = 0; $j < 7; $j++){
                $product = new Product();

                $product->setName($faker->sentence(4,  true))
                    ->setType($typeProduct)
                    ->setDescription($faker->paragraph(3, true))
                    ->setPrice($faker->randomNumber(4))
                    ->setReference($faker->ean8)
                    ->setStock($faker->randomDigit)
                    ->setProvider($faker->company)
                    ->setCreatedAt($faker->dateTime($max = 'now'))
                    ->setIsDeleted(false)
                ;

                $manager->persist($product);

            }

        }

        $manager->flush();
    }
}
