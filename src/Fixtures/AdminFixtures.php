<?php

namespace App\Fixtures;

use App\Entity\Admin;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        $superAdmin = new Admin();
        $superAdmin
            ->setUsername($faker->userName())
            ->setEmail('super_admin@mail.com')
            ->setPassword($this->hasher->hashPassword(user: $superAdmin, plainPassword: 'Password123!'))
            ->setLastName($faker->lastName())
            ->setFirstName($faker->firstName())
            ->setGender(['m', 'f'][mt_rand(0, 1)])
            ->setRoles(['ROLE_SUPER_ADMIN']);

        $manager->persist($superAdmin);

        for ($i = 2; $i <= 3; $i++) {
            $admin = new Admin();
            $admin
                ->setUsername($faker->userName())
                ->setEmail($faker->email())
                ->setPassword($this->hasher->hashPassword(user: $admin, plainPassword: 'Password123!'))
                ->setLastName($faker->lastName())
                ->setFirstName($faker->firstName())
                ->setGender(['m', 'f'][mt_rand(0, 1)])
                ->setRoles(['ROLE_ADMIN']);

            $manager->persist($admin);
        }

        $manager->flush();
    }
}
