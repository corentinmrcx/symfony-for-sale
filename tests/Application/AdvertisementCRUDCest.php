<?php

declare(strict_types=1);

namespace App\Tests\Application;

use App\Entity\Advertisement;
use App\Factory\AdvertisementFactory;
use App\Factory\CategoryFactory;
use App\Factory\UserFactory;
use Tests\Support\ApplicationTester;

final class AdvertisementCRUDCest
{
    private int $categoryId;

    public function _before(ApplicationTester $I): void
    {
        $this->categoryId = (int) CategoryFactory::createOne(['name' => 'Général'])->_real()->getId();
        UserFactory::createOne([
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'firstname' => 'Test',
            'lastname' => 'User',
            'roles' => ['ROLE_USER'],
        ]);
    }

    public function createAdvertisement(ApplicationTester $I): void
    {
        $I->amOnPage('/login');
        $I->fillField('_username', 'testuser@example.com');
        $I->fillField('_password', 'password123');
        $I->click('Se connecter');
        $I->amOnPage('/advertisement/new');
        $token = $I->grabValueFrom('input[name="advertisement[_token]"]');

        $I->submitForm(
            'form[name=advertisement]',
            [
                'advertisement[title]' => 'Titre annonce',
                'advertisement[description]' => 'Description annonce.',
                'advertisement[price]' => '500',
                'advertisement[location]' => 'Reims',
                'advertisement[category]' => (string) $this->categoryId,
                'advertisement[_token]' => $token,
            ],
            'Publier l\'annonce'
        );

        $I->seeInRepository(Advertisement::class, ['title' => 'Titre annonce']);
    }

    public function showAdvertisementAndAttributes(ApplicationTester $I): void
    {
        $ref = AdvertisementFactory::createOne([
            'title' => 'Voiture Renault Clio',
            'description' => 'Très bon état, 120000 km',
        ]);
        $ad = $ref->_real();
        $I->amOnPage(sprintf('/advertisement/%d', $ad->getId()));
        $I->see('Voiture Renault Clio');
        $I->see('Très bon état, 120000 km');
        $I->see('Général');
    }

    public function editAdvertisementAndUpdatesDatabase(ApplicationTester $I): void
    {
        $ref = AdvertisementFactory::createOne([
            'title' => 'Iphone 17',
            'description' => '256 Go, très bon état',
            'price' => 700,
        ]);
        $ad = $ref->_real();
        $I->amOnPage(sprintf('/advertisement/%d/edit', $ad->getId()));
        $token = $I->grabValueFrom('input[name="advertisement[_token]"]');

        $I->submitForm(
            'form[name=advertisement]',
            [
                'advertisement[title]' => 'iPhone 17 Pro',
                'advertisement[description]' => '256 Go, très bon état, batterie récente.',
                'advertisement[price]' => '800',
                'advertisement[location]' => 'Reims',
                'advertisement[category]' => (string) $this->categoryId,
                'advertisement[_token]' => $token,
            ],
            "Publier l'annonce"
        );

        $I->seeInRepository(Advertisement::class, ['id' => $ad->getId(), 'title' => 'iPhone 17 Pro']);
        $I->dontSeeInRepository(Advertisement::class, ['id' => $ad->getId(), 'title' => 'Iphone 17']);
    }

    public function deleteAdvertisement(ApplicationTester $I): void
    {
        $ref = AdvertisementFactory::createOne([
            'title' => 'Clavier',
            'description' => 'Clavier mécanique RGB',
            'price' => 50,
        ]);
        $ad = $ref->_real();
        $I->amOnPage(sprintf('/advertisement/%d', $ad->getId()));
        $I->click('Supprimer');
        $I->dontSeeInRepository(Advertisement::class, ['id' => $ad->getId()]);
    }
}
