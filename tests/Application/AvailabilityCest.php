<?php

declare(strict_types=1);

namespace App\Tests\Application;

use App\Factory\AdvertisementFactory;
use App\Factory\CategoryFactory;
use Tests\Support\ApplicationTester;

final class AvailabilityCest
{
    private int $advertisementId;
    private int $categoryId;

    public function _before(ApplicationTester $I): void
    {
        $this->categoryId = (int) CategoryFactory::createOne(['name' => 'Test Category'])->_real()->getId();
        $this->advertisementId = (int) AdvertisementFactory::createOne([
            'title' => 'Test Advertisement',
            'description' => 'Test description',
        ])->_real()->getId();
    }

    /**
     * @group available
     *
     * @example ["/"]
     * @example ["/advertisement"]
     * @example ["/advertisement/new"]
     * @example ["/category"]
     * @example ["/login"]
     * @example ["/logout"]
     */
    public function pageIsAvailable(ApplicationTester $I, \Codeception\Example $example): void
    {
        $I->amOnPage($example[0]);
        $I->seeResponseCodeIs(200);
    }

    /**
     * @group available
     *
     * @example ["/advertisement/%d"]
     * @example ["/advertisement/%d/edit"]
     */
    public function pageWithAdvertisementIdIsAvailable(ApplicationTester $I, \Codeception\Example $example): void
    {
        $url = sprintf($example[0], $this->advertisementId);
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
    }

    /**
     * @group available
     *
     * @example ["/category/%d"]
     */
    public function pageWithCategoryIdIsAvailable(ApplicationTester $I, \Codeception\Example $example): void
    {
        $url = sprintf($example[0], $this->categoryId);
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
    }
}
