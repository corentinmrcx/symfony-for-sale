<?php

namespace App\Story;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Zenstruck\Foundry\Story;

final class CategoryStory extends Story
{
    private array $categories = [];

    public function __construct(
        #[Autowire('%kernel.project_dir%/data')]
        private string $pathData,
    ) {
        $this->categories = file($this->pathData . '/category.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }

    public function build(): void
    {
        if (!empty($this->categories)) {
            $this->addState('category_without_advertisement', [
                'name' => $this->categories[0],
            ]);

            $categoriesPool = array_slice($this->categories, 1);
            if (!empty($categoriesPool)) {
                $this->addPool('categories', $categoriesPool);
            }
        }
    }
}
