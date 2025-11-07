<?php

namespace App\DataFixtures;

use App\Factory\AdvertisementFactory;
use App\Story\CategoryStory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

use function Zenstruck\Foundry\Persistence\flush_after;

class AdvertisementFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        flush_after(function () {
            AdvertisementFactory::createMany(500, function () {
                return [
                    'category' => CategoryStory::getRandom('categories'),
                ];
            });
        });
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}