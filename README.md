# Symfony for sale
## Corentin Marcoux
## Installation / Configuration

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
| `composer db`           | Réinitialise la base de données : suppression, création, migrations et chargement des fixtures.    |