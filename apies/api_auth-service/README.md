# Déploiement API Auth

## Prérequis :

- Docker

## Installation :

### Dépendance :

- `composer install`

### Base de données :

Pour l'API Auth vous avez besoin d'importer les données de la base de données qui se trouve dans le dossier sql :

- `auth_fake` => Donnée généré par faker pour les tests
- `auth_schema` => Structure de la base de données vide

Assurez-vous d'avoir aussi bien configuré les variables d'environnement dans le fichier `auth.db.ini.dist`.

### Lancement :

Dans le dossier racine du répertoire git :

- `docker-compose up -d`

Cela lancera toutes les API avec Adminer pour la gestion de la base de données.
