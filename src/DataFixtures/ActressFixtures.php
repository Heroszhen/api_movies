<?php

namespace App\DataFixtures;

use App\Entity\Actress;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ActressFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private ParameterBagInterface $parameterBag
    )
    {}

    public function load(ObjectManager $manager): void
    {
        $publicDir = $this->parameterBag->get('public_dir');
        $imageUrl = "{$publicDir}/assets/static/test/naruto-314079d37756cd0ca99aba406d134be8.png";
        $fileToUpload = new UploadedFile($imageUrl, 'naruto.png', 'image/png', true);

        $faker = Factory::create();
        for($i = 0; $i < 5; $i++) {
            $actress = new Actress();
            $actress
                ->setName($faker->name())
                ->setCountry($faker->country())
                ->setBirthday($faker->dateTime())
                ->setDescription($faker->realText(rand(150, 200)))
            ;

            $manager->persist($actress);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
