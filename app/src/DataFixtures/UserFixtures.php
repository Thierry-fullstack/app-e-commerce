<?php

namespace App\DataFixtures;


use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher){}


    public function load(ObjectManager $manager): void
    {
         $faker = Factory::create('fr_FR');

         $admin = new User();
         $admin->setEmail('ted@gmx.fr')->setRoles(['ROLE_ADMIN'])->setCreatedAt(new DateTimeImmutable())->setIsCompleted(true);
         $admin->setPassword($this->passwordHasher->hashPassword($admin,'ArethiA75!'));
         $admin->setLastname($faker->lastName())->setFirstname($faker->firstName())->setAddress($faker->address())
             ->setZipcode($faker->postcode())->setCity($faker->city());
         $manager->persist($admin);

        $author = new User();
        $author->setEmail($faker->email())->setRoles(['ROLE_GESTION'])->setCreatedAt(new DateTimeImmutable())->setIsCompleted(true);
        $author->setPassword($this->passwordHasher->hashPassword($author,'ArethiA75!'));
        $author->setLastname($faker->lastName())->setFirstname($faker->firstName())->setAddress($faker->address())
            ->setZipcode($faker->postcode())->setCity($faker->city());
        $manager->persist($author);

        $user = new User();
        $user->setEmail($faker->email())->setRoles(['ROLE_USER'])->setCreatedAt(new DateTimeImmutable())->setIsCompleted(true);
        $user->setPassword($this->passwordHasher->hashPassword($user,'ArethiA75!'));
        $user->setLastname($faker->lastName())->setFirstname($faker->firstName())->setAddress($faker->address())
            ->setZipcode($faker->postcode())->setCity($faker->city());
        $manager->persist($user);

        $manager->flush();
    }
}
