<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {}

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user
            ->setEmail('herosgogogogo@gmail.com')
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
            ->setLastname('Hero')
            ->setFirstname('zhen')
        ;
        $plaintextPassword = 'aaaaaaaa';
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);

        $manager->persist($user);


        $user = new User();
        $user
            ->setEmail('zyang@sogec-marketing.fr')
            ->setRoles(['ROLE_USER'])
            ->setLastname('Hero2')
            ->setFirstname('zhen2')
        ;
        $plaintextPassword = 'bbbbbbbb';
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);

        $manager->persist($user);


        $faker = Factory::create();
        for($i = 0; $i < 5; $i++) {
            $user = new User();
            $user
                ->setEmail($faker->email())
                ->setLastname($faker->lastName())
                ->setFirstname($faker->firstName())
            ;
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                '123456789'
            );
            $user->setPassword($hashedPassword);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
