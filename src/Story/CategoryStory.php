<?php

namespace App\Story;

use App\Factory\CategoryFactory;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Zenstruck\Foundry\Story;

final class CategoryStory extends Story
{
    public function __construct(
        #[Autowire('%kernel.project_dir%/data/')]
        private readonly string $dataDir,
    ) {
    }

    public function build(): void
    {
        $filePath = $this->dataDir.'category.txt';
        if (!file_exists($filePath)) {
            throw new \Exception('Le fichier category.txt est introuvable.');
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        [$first] = $lines;
        $this->addState('category_without_advertisement', CategoryFactory::createOne(['name' => trim($first)]));
        foreach (array_slice($lines, 1) as $line) {
            $this->addToPool('categories', CategoryFactory::createOne(['name' => trim($line)]));
        }
    }
}