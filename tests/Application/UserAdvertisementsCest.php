<?php

declare(strict_types=1);

namespace App\Tests\Application;

use App\Factory\AdvertisementFactory;
use App\Factory\CategoryFactory;
use App\Factory\UserFactory;
use Tests\Support\ApplicationTester;

final class UserAdvertisementsCest
{
    public function _before(ApplicationTester $I): void
    {
        // Créer une catégorie par défaut pour tous les tests
        CategoryFactory::createOne(['name' => 'Test Category']);
    }

    public function userCanSeeTheirOwnAdvertisements(ApplicationTester $I): void
    {
        $user = UserFactory::createOne([
            'email' => 'user@example.com',
            'firstname' => 'John',
            'lastname' => 'Doe',
        ]);

        AdvertisementFactory::createOne([
            'title' => 'Mon annonce 1',
            'description' => 'Description de mon annonce 1',
            'owner' => $user,
        ]);

        AdvertisementFactory::createOne([
            'title' => 'Mon annonce 2',
            'description' => 'Description de mon annonce 2',
            'owner' => $user,
        ]);

        // Créer une annonce d'un autre utilisateur
        $otherUser = UserFactory::createOne(['email' => 'other@example.com']);
        AdvertisementFactory::createOne([
            'title' => 'Annonce d\'un autre utilisateur',
            'description' => 'Description d\'une autre annonce',
            'owner' => $otherUser,
        ]);

        $userId = (int) $user->_real()->getId();
        $I->amOnPage("/user/{$userId}/advertisements");
        $I->seeResponseCodeIs(200);
        $I->see('Mon annonce 1');
        $I->see('Mon annonce 2');
        $I->dontSee('Annonce d\'un autre utilisateur');
    }

    public function authenticatedUserCanAccessTheirAdvertisementsViaNavbar(ApplicationTester $I): void
    {
        $user = UserFactory::createOne([
            'email' => 'authenticated@example.com',
            'password' => 'password123',
            'firstname' => 'Jane',
            'lastname' => 'Smith',
        ]);

        AdvertisementFactory::createOne([
            'title' => 'Ma première annonce',
            'description' => 'Description de ma première annonce',
            'owner' => $user,
        ]);

        // Simuler une connexion
        $I->amLoggedInAs($user->_real());
        $I->amOnPage('/');
        $I->see('Mes annonces');
        $I->click('Mes annonces');
        $I->seeInCurrentUrl('/user/');
        $I->seeInCurrentUrl('/advertisements');
        $I->see('Ma première annonce');
    }

    public function unauthenticatedUserCannotSeeMyAdvertisementsLinkInNavbar(ApplicationTester $I): void
    {
        $I->amOnPage('/');
        $I->dontSee('Mes annonces');
    }

    public function userWithNoAdvertisementsSeeEmptyMessage(ApplicationTester $I): void
    {
        $user = UserFactory::createOne([
            'email' => 'noads@example.com',
            'firstname' => 'Empty',
            'lastname' => 'User',
        ]);

        $userId = (int) $user->_real()->getId();
        $I->amOnPage("/user/{$userId}/advertisements");
        $I->seeResponseCodeIs(200);
        $I->see('Aucune annonce');
    }
}
