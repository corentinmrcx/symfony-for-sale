<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Factory\AdvertisementFactory;

class AdvertisementFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        AdvertisementFactory::createMany(500);
        $manager->flush();
    }
}
