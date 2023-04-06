# Déploiement API Tedyspo

## Prérequis :

- Docker

## Installation :

### Dépendance :

- `composer install`

### Base de données :

Pour l'API Tedyspo vous avez besoin d'importer les données de la base de données qui se trouve dans le dossier sql :

- `tedyspo_fake` => Donnée généré par faker pour les tests
- `tedyspo_schema` => Structure de la base de données vide

Assurez-vous d'avoir aussi bien configuré les variables d'environnement dans le fichier `tedyspo.db.ini.dist`.

### Lancement :

Dans le dossier racine du répertoire git :

- `docker-compose up -d`

Cela lancera toutes les API avec Adminer pour la gestion de la base de données.
