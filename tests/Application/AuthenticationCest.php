<?php

declare(strict_types=1);

namespace App\Tests\Application;

use App\Factory\UserFactory;
use Tests\Support\ApplicationTester;

final class AuthenticationCest
{
    public function _before(ApplicationTester $I): void
    {
        UserFactory::createOne([
            'email' => 'test@example.com',
            'password' => 'password123',
            'firstname' => 'Test',
            'lastname' => 'User',
            'roles' => ['ROLE_USER'],
        ]);
    }

    public function userCanLogin(ApplicationTester $I): void
    {
        $I->amOnPage('/login');
        $I->see('Connexion');
        $I->fillField('_username', 'test@example.com');
        $I->fillField('_password', 'password123');
        $I->click('Se connecter');
        $I->see('test@example.com');
        $I->see('Se déconnecter');
    }

    public function loggedInUserCanLogout(ApplicationTester $I): void
    {
        $I->amOnPage('/login');
        $I->fillField('_username', 'test@example.com');
        $I->fillField('_password', 'password123');
        $I->click('Se connecter');
        $I->see('test@example.com');
        $I->click('Se déconnecter');
        $I->see('Connexion');
        $I->dontSee('test@example.com');
    }

    public function loginWithWrongCredentialsFails(ApplicationTester $I): void
    {
        $I->amOnPage('/login');
        $I->see('Connexion');
        $I->fillField('_username', 'test@example.com');
        $I->fillField('_password', 'wrongpassword');
        $I->click('Se connecter');
        $I->see('Identifiants invalides');
    }

    public function unauthenticatedUserCannotAccessNewAdvertisementPage(ApplicationTester $I): void
    {
        $I->amOnPage('/advertisement/new');
        $I->seeCurrentUrlEquals('/login');
        $I->see('Connexion');
    }
}
