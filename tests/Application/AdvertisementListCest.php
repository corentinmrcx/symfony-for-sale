<?php

declare(strict_types=1);

namespace App\Tests\Application;

use App\Factory\AdvertisementFactory;
use App\Factory\CategoryFactory;
use Tests\Support\ApplicationTester;

final class AdvertisementListCest
{
    public function _before(ApplicationTester $I): void
    {
        // Code here will be executed before each test.
    }

    public function listIsEmptyDisplaysCorrectly(ApplicationTester $I): void
    {
        $I->amOnPage('/advertisement');
        $I->seeElement('.list-group');
        $I->see('Aucune annonce');
    }

    public function listShow20ItemsWithPagination(ApplicationTester $I): void
    {
        CategoryFactory::createOne();
        AdvertisementFactory::createMany(20);
        $I->amOnPage('/advertisement');
        $I->seeElement('.pagination');
        $I->seeNumberOfElements('.list-group-item', 15);
        $I->click('2', '.pagination');
        $I->seeNumberOfElements('.list-group-item', 5);
    }
}
