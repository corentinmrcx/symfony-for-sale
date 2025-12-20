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

    public function showAdvertisement(ApplicationTester $I): void
    {
        $ref = AdvertisementFactory::createOne([
            'title' => 'Voiture Renault Clio',
            'description' => 'Très bon état, 120000 km',
            'price' => 5000,
            'location' => 'Paris',
        ]);
        $ad = $ref->_real();

        $I->amOnPage(sprintf('/advertisement/%d', $ad->getId()));
        $I->see('Voiture Renault Clio');
        $I->see('Très bon état, 120000 km');
        $I->see('Général');
    }

    public function editAdvertisementAndUpdatesDatabase(ApplicationTester $I): void
    {
        $owner = UserFactory::createOne([
            'email' => 'editowner@example.com',
            'password' => 'password123',
            'firstname' => 'EditOwner',
            'lastname' => 'User',
            'roles' => ['ROLE_USER'],
        ])->_real();

        $ref = AdvertisementFactory::createOne([
            'title' => 'Iphone 17',
            'description' => '256 Go, très bon état',
            'price' => 700,
            'owner' => $owner,
        ]);
        $ad = $ref->_real();

        $I->amOnPage('/login');
        $I->fillField('_username', 'editowner@example.com');
        $I->fillField('_password', 'password123');
        $I->click('Se connecter');

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
        $owner = UserFactory::createOne([
            'email' => 'deleteowner@example.com',
            'password' => 'password123',
            'firstname' => 'DeleteOwner',
            'lastname' => 'User',
            'roles' => ['ROLE_USER'],
        ])->_real();

        $ref = AdvertisementFactory::createOne([
            'title' => 'Clavier',
            'description' => 'Clavier mécanique RGB',
            'price' => 50,
            'owner' => $owner,
        ]);
        $ad = $ref->_real();

        $I->amOnPage('/login');
        $I->fillField('_username', 'deleteowner@example.com');
        $I->fillField('_password', 'password123');
        $I->click('Se connecter');

        $I->amOnPage(sprintf('/advertisement/%d', $ad->getId()));
        $I->click('Supprimer');
        $I->dontSeeInRepository(Advertisement::class, ['id' => $ad->getId()]);
    }

    public function cannotEditAdvertisementOfOtherUser(ApplicationTester $I): void
    {
        $owner = UserFactory::createOne([
            'email' => 'owner@example.com',
            'password' => 'password123',
            'firstname' => 'Owner',
            'lastname' => 'User',
            'roles' => ['ROLE_USER'],
        ])->_real();

        $ref = AdvertisementFactory::createOne([
            'title' => 'Annonce du propriétaire',
            'description' => 'Cette annonce appartient à owner@example.com',
            'price' => 100,
            'owner' => $owner,
        ]);
        $ad = $ref->_real();

        UserFactory::createOne([
            'email' => 'other@example.com',
            'password' => 'password123',
            'firstname' => 'Other',
            'lastname' => 'User',
            'roles' => ['ROLE_USER'],
        ]);

        $I->amOnPage('/login');
        $I->fillField('_username', 'other@example.com');
        $I->fillField('_password', 'password123');
        $I->click('Se connecter');
        $I->amOnPage(sprintf('/advertisement/%d/edit', $ad->getId()));
        $I->seeResponseCodeIs(403);
    }

    public function cannotDeleteAdvertisementOfOtherUser(ApplicationTester $I): void
    {
        $owner = UserFactory::createOne([
            'email' => 'owner2@example.com',
            'password' => 'password123',
            'firstname' => 'Owner2',
            'lastname' => 'User',
            'roles' => ['ROLE_USER'],
        ])->_real();

        $ref = AdvertisementFactory::createOne([
            'title' => 'Annonce à ne pas supprimer',
            'description' => 'Cette annonce appartient à owner2@example.com',
            'price' => 200,
            'owner' => $owner,
        ]);
        $ad = $ref->_real();

        UserFactory::createOne([
            'email' => 'other2@example.com',
            'password' => 'password123',
            'firstname' => 'Other2',
            'lastname' => 'User',
            'roles' => ['ROLE_USER'],
        ]);

        $I->amOnPage('/login');
        $I->fillField('_username', 'other2@example.com');
        $I->fillField('_password', 'password123');
        $I->click('Se connecter');
        $I->amOnPage(sprintf('/advertisement/%d', $ad->getId()));
        // Vérifier que le bouton Supprimer n'est pas présent dans le formulaire
        $I->dontSeeElement('form[action*="/advertisement/'.$ad->getId().'"] button.btn-danger');
        $I->seeInRepository(Advertisement::class, ['id' => $ad->getId()]);
    }

    public function ownerCanEditTheirOwnAdvertisement(ApplicationTester $I): void
    {
        $owner = UserFactory::createOne([
            'email' => 'owner3@example.com',
            'password' => 'password123',
            'firstname' => 'Owner3',
            'lastname' => 'User',
            'roles' => ['ROLE_USER'],
        ])->_real();

        $ref = AdvertisementFactory::createOne([
            'title' => 'Mon annonce',
            'description' => 'Description originale',
            'price' => 300,
            'owner' => $owner,
        ]);
        $ad = $ref->_real();

        $I->amOnPage('/login');
        $I->fillField('_username', 'owner3@example.com');
        $I->fillField('_password', 'password123');
        $I->click('Se connecter');
        $I->amOnPage(sprintf('/advertisement/%d/edit', $ad->getId()));
        $token = $I->grabValueFrom('input[name="advertisement[_token]"]');
        $I->submitForm(
            'form[name=advertisement]',
            [
                'advertisement[title]' => 'Mon annonce modifiée',
                'advertisement[description]' => 'Description mise à jour',
                'advertisement[price]' => '350',
                'advertisement[location]' => 'Reims',
                'advertisement[category]' => (string) $this->categoryId,
                'advertisement[_token]' => $token,
            ],
            "Publier l'annonce"
        );
        $I->seeInRepository(Advertisement::class, ['id' => $ad->getId(), 'title' => 'Mon annonce modifiée']);
    }

    public function ownerCanDeleteTheirOwnAdvertisement(ApplicationTester $I): void
    {
        $owner = UserFactory::createOne([
            'email' => 'owner4@example.com',
            'password' => 'password123',
            'firstname' => 'Owner4',
            'lastname' => 'User',
            'roles' => ['ROLE_USER'],
        ])->_real();

        $ref = AdvertisementFactory::createOne([
            'title' => 'Annonce à supprimer',
            'description' => 'Je veux la supprimer',
            'price' => 400,
            'owner' => $owner,
        ]);
        $ad = $ref->_real();

        $I->amOnPage('/login');
        $I->fillField('_username', 'owner4@example.com');
        $I->fillField('_password', 'password123');
        $I->click('Se connecter');
        $I->amOnPage(sprintf('/advertisement/%d', $ad->getId()));
        $I->see('Supprimer');
        $I->click('Supprimer');
        $I->dontSeeInRepository(Advertisement::class, ['id' => $ad->getId()]);
    }
}
