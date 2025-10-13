<?php

namespace App\DataFixtures;

use AllowDynamicProperties;
use App\Factory\CategoryFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class CategoryFixtures extends Fixture
{

    public function __construct(
        #[Autowire('%kernel.project_dir%/data')]
        private string $pathData,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $file = $this -> pathData . '/category.txt';
        if (!file_exists($file)) {
            throw new \RuntimeException("Fichier introuvable.");
        }
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            CategoryFactory::createOne(['name' => trim($line)]);
        }
    }
}
