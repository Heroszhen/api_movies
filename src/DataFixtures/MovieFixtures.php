<?php

namespace App\DataFixtures;

use App\Entity\Actress;
use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class MovieFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for($i = 0; $i < 20; $i++) {
            $name = "";
            for($j = 0; $j < 5; $j++) {
                $name .= $faker->word();
                if ($j < 4) {
                    $name .= ' ';
                }
            }

            $movie = new Movie();
            $movie
                ->setName($name)
                ->setLast($faker->numberBetween(80, 100))
                ->setDescription($faker->paragraph(10))
                ->setReleased($faker->dateTime())
            ;
            for ($j = 1; $j < 5; $j++) {
                $actress = $manager->find(Actress::class, $j);
                $movie->addActor($actress);
            }

            $manager->persist($movie);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ActressFixtures::class,
        ];
    }
}