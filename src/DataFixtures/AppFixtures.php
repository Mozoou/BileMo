<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Client;
use App\Entity\MobilePhone;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {}

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 16; $i++) {
            $company = new User();
            $company->setEmail('company' . $i . '@example.com');
            $hashedPassword = $this->passwordHasher->hashPassword(
                $company,
                'company'.$i
            );
            $company->setPassword($hashedPassword);
            $company->setRoles([User::ROLE_USER]);

            for ($j = 0; $j < 3; $j++) { 
                $client = new Client();
                $client->setCompany($company);
                $client->setFirstName('ClientFirstName_'. $j);
                $client->setLastName('ClientLastName_'. $j);
                $client->setUsername('ClientUsername'.$i);
                $client->setEmail('client'. $j . '@example.com');

                $manager->persist($client);
            }

            $manager->persist($company);

            $brand = new Brand();
            $brand->setName('PhoneBrand');
            $brand->setCountryCode('FR');
            $manager->persist($brand);

            $mobilePhone = new MobilePhone();
            $mobilePhone->setBrand($brand);
            $mobilePhone->setName('Phone_' . $i);
            $mobilePhone->setPrice(rand(200, 900));
            $mobilePhone->setDescription('This is a mobile phone description !');

            $manager->persist($mobilePhone);
        }


        $manager->flush();
    }
}
