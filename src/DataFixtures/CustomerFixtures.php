<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CustomerFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        $types = ['customer', 'supplier', 'other'];

        for ($i = 0; $i < 20; $i++){
            $customer = new Customer();

            $customer->setName($faker->name())
                ->setType($types[rand(0, 2)])
                ->setEmail($faker->email)
                ->setPhone($faker->e164PhoneNumber)
                ->setAddress($faker->streetAddress)
                ->setCity($faker->city)
                ->setPostalCode($faker->postcode)
                ->setCountry($faker->country)
                ->setCode($faker->ean8)
            ;

            $manager->persist($customer);
        }

        $manager->flush();
    }
}
