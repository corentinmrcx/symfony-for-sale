<?php

namespace App\Story;

use App\Factory\UserFactory;
use Zenstruck\Foundry\Story;

final class UserStory extends Story
{
    public function build(): void
    {
        // Administrateurs
        UserFactory::createOne([
            'email' => 'admin@example.com',
            'firstname' => 'Admin',
            'lastname' => 'Principal',
            'roles' => ['ROLE_ADMIN'],
            'password' => 'test',
        ]);

        UserFactory::createOne([
            'email' => 'admin2@example.com',
            'firstname' => 'Admin',
            'lastname' => 'Secondaire',
            'roles' => ['ROLE_ADMIN'],
            'password' => 'test',
        ]);

        // Utilisateurs standards
        UserFactory::createOne([
            'email' => 'user@example.com',
            'firstname' => 'User',
            'lastname' => 'Principal',
            'roles' => ['ROLE_USER'],
            'password' => 'test',
        ]);

        UserFactory::createOne([
            'email' => 'user2@example.com',
            'firstname' => 'User',
            'lastname' => 'Secondaire',
            'roles' => ['ROLE_USER'],
            'password' => 'test',
        ]);

        // 10 utilisateurs aléatoires
        UserFactory::createMany(10, [
            'password' => 'test',
        ]);
    }
}
