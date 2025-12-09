<?php

declare(strict_types=1);

namespace App\Tests\Application;

use Tests\Support\ApplicationTester;

final class HomeCest
{
    public function _before(ApplicationTester $I): void
    {
        // Code here will be executed before each test.
    }

    public function homeRedirectsToAdvertisementList(ApplicationTester $I): void
    {
        $I->amOnPage('/');
        $I->seeCurrentUrlEquals('/advertisement');
        $I->see('Liste des annonces');
    }
}
