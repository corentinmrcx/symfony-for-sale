<?php

namespace App\Factory;

use App\Entity\Advertisement;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Advertisement>
 */
final class AdvertisementFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Advertisement::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @return array<string, mixed>
     * @todo add your default values here
     */
    protected function defaults(): array
    {
        return [
            'title' => self::faker()->text(100),
            'description' => self::faker()->text(500),
            'price' => self::faker()->randomFloat(2, 0, 1500),
            'location' => self::faker()->city(),
            'createdAt' => \DateTimeImmutable::createFromMutable(
                self::faker()->dateTimeBetween('-2 months', 'now')
            ),
            'category' => CategoryFactory::random(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Advertisement $advertisement): void {})
        ;
    }
}
