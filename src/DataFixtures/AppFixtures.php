<?php

namespace App\DataFixtures;

use App\Entity\Product;
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
            $user = new User();
            $user->setEmail('email' . $i . '@example.com');
            $user->setFirstname('firstname' . $i);
            $user->setLastname('lastname' . $i);
            $user->setUsername('username'.$i);
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                'user'.$i
            );
            $user->setPassword($hashedPassword);
            $user->setRoles([User::ROLE_USER]);
            $manager->persist($user);


            $product = new Product();
            $product->setName('Product' . $i);
            $product->setPrice(rand(1, 9));
            $product->setDescription('This is a product description !');

            $manager->persist($product);
        }


        $manager->flush();
    }
}
