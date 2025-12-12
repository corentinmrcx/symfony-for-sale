<?php

namespace App\DataFixtures;

use App\Entity\User;
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
        flush_after(function () use ($manager) {
            $randomUsers = $manager->getRepository(User::class)->createQueryBuilder('u')
                ->where('u.email NOT IN (:emails)')
                ->setParameter('emails', ['admin@example.com', 'admin2@example.com', 'user@example.com', 'user2@example.com'])
                ->getQuery()
                ->getResult();

            $userPrincipal = $manager->getRepository(User::class)->findOneBy(['email' => 'user@example.com']);

            AdvertisementFactory::createMany(500, function () use ($randomUsers) {
                return [
                    'category' => CategoryStory::getRandom('categories'),
                    'owner' => $randomUsers[array_rand($randomUsers)],
                ];
            });

            AdvertisementFactory::createMany(20, function () use ($userPrincipal) {
                return [
                    'category' => CategoryStory::getRandom('categories'),
                    'owner' => $userPrincipal,
                ];
            });
        });
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class,
        ];
    }
}
