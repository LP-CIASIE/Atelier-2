# Déploiement API Gateway

## Prérequis :

- Docker

## Installation :

### Dépendance :

- `composer install`

### Base de données :

L'API Gateway ne nécessite pas de base de données, mais dépend des deux autres API (Auth et Tedyspo).

### Lancement :

Dans le dossier racine du répertoire git :

- `docker-compose up -d`

Cela lancera toutes les API avec Adminer pour la gestion de la base de données.
