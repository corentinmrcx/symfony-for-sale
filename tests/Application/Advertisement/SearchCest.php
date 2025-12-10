<?php

declare(strict_types=1);

namespace App\Tests\Application\Advertisement;

use App\Factory\AdvertisementFactory;
use App\Factory\CategoryFactory;
use Tests\Support\ApplicationTester;

final class SearchCest
{
    public function _before(ApplicationTester $I): void
    {
        // Code here will be executed before each test.
    }

    public function searchFormIsDisplayedInNavbar(ApplicationTester $I): void
    {
        $I->amOnPage('/advertisement');
        $I->seeElement('form[action="/advertisement"]');
        $I->seeElement('input[name="search"]');
    }

    public function searchByTitleDisplaysCorrectResults(ApplicationTester $I): void
    {
        $category = CategoryFactory::createOne();
        AdvertisementFactory::createOne(['title' => 'Vends vélo', 'description' => 'Un super vélo', 'category' => $category]);
        AdvertisementFactory::createOne(['title' => 'Achète voiture', 'description' => 'Je cherche une voiture', 'category' => $category]);
        AdvertisementFactory::createOne(['title' => 'Vends moto', 'description' => 'Belle moto', 'category' => $category]);

        $I->amOnPage('/advertisement');
        $I->fillField('search', 'vélo');
        $I->click('button[type="submit"][aria-label="Rechercher"]');

        $I->seeCurrentUrlEquals('/advertisement?search=v%C3%A9lo');
        $I->see('Recherche : vélo', 'h1');
        $I->see('Vends vélo');
        $I->dontSee('Achète voiture');
        $I->dontSee('Vends moto');
    }

    public function searchByDescriptionDisplaysCorrectResults(ApplicationTester $I): void
    {
        $category = CategoryFactory::createOne();
        AdvertisementFactory::createOne(['title' => 'Annonce 1', 'description' => 'Urgent à vendre', 'category' => $category]);
        AdvertisementFactory::createOne(['title' => 'Annonce 2', 'description' => 'Bon état', 'category' => $category]);
        AdvertisementFactory::createOne(['title' => 'Annonce 3', 'description' => 'Urgent cherche acheteur', 'category' => $category]);

        $I->amOnPage('/advertisement');
        $I->fillField('search', 'urgent');
        $I->click('button[type="submit"][aria-label="Rechercher"]');

        $I->see('Recherche : urgent', 'h1');
        $I->see('Annonce 1');
        $I->dontSee('Annonce 2');
        $I->see('Annonce 3');
    }

    public function searchIsCaseInsensitive(ApplicationTester $I): void
    {
        $category = CategoryFactory::createOne();
        AdvertisementFactory::createOne(['title' => 'ORDINATEUR Portable', 'description' => 'Excellent état', 'category' => $category]);
        AdvertisementFactory::createOne(['title' => 'Téléphone', 'description' => 'ORDINATEUR fixe inclus', 'category' => $category]);

        $I->amOnPage('/advertisement');
        $I->fillField('search', 'ordinateur');
        $I->click('button[type="submit"][aria-label="Rechercher"]');

        $I->see('ORDINATEUR Portable');
        $I->see('Téléphone');
    }

    public function emptySearchDisplaysAllAdvertisements(ApplicationTester $I): void
    {
        $category = CategoryFactory::createOne();
        AdvertisementFactory::createMany(5, ['category' => $category]);

        $I->amOnPage('/advertisement?search=');
        $I->see('Liste des annonces', 'h1');
        $I->seeNumberOfElements('.list-group-item', 5);
    }

    public function searchValueIsKeptInFormField(ApplicationTester $I): void
    {
        $category = CategoryFactory::createOne();
        AdvertisementFactory::createOne(['title' => 'Test annonce', 'description' => 'Description test', 'category' => $category]);

        $I->amOnPage('/advertisement?search=test');
        $I->seeInField('search', 'test');
    }
}
