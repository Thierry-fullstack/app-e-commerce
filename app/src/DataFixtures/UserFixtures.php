<?php

namespace App\DataFixtures;


use App\Entity\Customer;
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
         $admin->setEmail('ted@gmx.fr')->setRoles(['ROLE_ADMIN'])->setCreatedAt(new DateTimeImmutable())->setStep('C');
         $admin->setPassword($this->passwordHasher->hashPassword($admin,'ArethiA75!'));
         $manager->persist($admin);
         $customer = new Customer();
         $customer->setLastname('dothee')->setFirstname('thierry')->setAddress('1 rue du cimetière')
             ->setZipcode('94500')->setCity('Champigny sur marne')->setPhone('0784835544')->setUser($admin);
         $manager->persist($customer);
        $manager->flush();

        for($i = 0; $i < 5; $i++){
            $user = new User();
            $user->setEmail($faker->email())->setRoles(['ROLE_USER'])->setCreatedAt(new DateTimeImmutable())->setStep('C');
            $user->setPassword($this->passwordHasher->hashPassword($user,'ArethiA75!'));
            $manager->persist($user);
            $customer = new Customer();
            $customer->setLastname($faker->lastName)->setFirstname($faker->firstName)->setAddress($faker->address())
                ->setZipcode($faker->postcode)->setCity($faker->city())->setPhone($faker->serviceNumber())->setUser($user);
            $manager->persist($customer);
        }
        $manager->flush();
    }
}
