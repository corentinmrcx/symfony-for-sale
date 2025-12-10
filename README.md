# Symfony for sale
## Corentin Marcoux
## Installation / Configuration

## Utilisateurs factices

Les fixtures créent automatiquement les utilisateurs suivants (mot de passe : `test`) :

### Administrateurs
| Email                | Prénom | Nom         | Rôle         |
|----------------------|--------|-------------|--------------|
| admin@example.com    | Admin  | Principal   | ROLE_ADMIN   |
| admin2@example.com   | Admin  | Secondaire  | ROLE_ADMIN   |

### Utilisateurs standards
| Email                | Prénom | Nom         | Rôle         |
|----------------------|--------|-------------|--------------|
| user@example.com     | User   | Principal   | ROLE_USER    |
| user2@example.com    | User   | Secondaire  | ROLE_USER    |

### Utilisateurs aléatoires
10 utilisateurs supplémentaires avec des données aléatoires (nom, prénom, email) et le rôle `ROLE_USER`.

**Mot de passe pour tous les utilisateurs** : `test`

## Scripts Composer

| Script                | Description                                                                                           |
|-----------------------|------------------------------------------------------------------------------------------------------|
| `composer start`      | Démarre le serveur de développement Symfony.                                                         |
| `composer test:csfixer` | Vérifie le style de code PHP sans appliquer de modifications.                                      |
| `composer fix:csfixer`  | Corrige automatiquement le style de code PHP.                                                      |
| `composer test:phpstan` | Analyse statique du code PHP avec PHPStan.                                                         |
| `composer fix:phpstan`  | Génère ou régénère le baseline PHPStan.                                                            |
| `composer test:twig`    | Vérifie la conformité des templates Twig.                                                          |
| `composer fix:twig`     | Corrige automatiquement le style des templates Twig.                                               |
| `composer test:yaml`    | Valide la syntaxe des fichiers YAML du dossier `config/`.                                          |
| `composer test`         | Exécute tous les tests et analyses (PHP, PHPStan, Twig, YAML).                                     |
| `composer fix`          | Applique toutes les corrections automatiques disponibles.                                          |
| `composer db`           | Réinitialise la base de données : suppression, création, migrations et chargement des fixtures.    |
