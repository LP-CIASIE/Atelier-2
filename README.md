# Atelier-2 :

Créateurs : Bradley Barbier, Christopher Jue, Teddy Clement Dels, Cyprien Cotinaut, Ugo Zanzi

---

# But du projet :

Reunionou.app est une application qui permet à des groupes de personnes (famille, amis,
groupe de collègues) de se fixer un rendez-vous en un lieu déterminé et d'organiser ce rendez-
vous. L'application est disponible en version web (ordinateurs et terminaux mobiles) et en
version mobile (smartphones, tablettes).
La version mobile est réservée aux utilisateurs inscrits sur la plateforme, alors que la version
web propose certaines fonctionnalités qui sont également accessibles à des usagers non
inscrits.
L'application permet principalement de :
• fixer le lieu et la date du point de rencontre,
• diffuser et partager cette information,
• recenser les participants,
• offrir des services complémentaires : météo, trajet vers le point de RV, points
d’intérêts aux alentours de ce RV.

---

# Déploiement

## Prérequis :

- Docker
- NodeJS
- Flutter

## Installation :

### Backend :

##### Dépendance :

Pour chacun des dossiers présents dans le dossier `apies` (hormis `fake_data`) :

- `cd <nom_du_dossier>`
- `composer install`

##### Base de données :

Pour les API Auth et Tedyspo vous avez besoin d'importer les données de la base de données qui se trouve dans le dossier sql :

- `<bdd>_fake` => Donnée généré par faker pour les tests
- `<bdd>_schema` => Structure de la base de données vide

Assurez-vous d'avoir aussi bien configuré les variables d'environnement dans le fichier `<bdd>.db.ini.dist` de chaque dossier.

##### Lancement :

Dans le dossier racine du répertoire git :

- `docker-compose up -d`

Cela lancera toutes les API avec Adminer pour la gestion de la base de données.

### Frontend :

- `cd frontend`
- `npm install`
- `npm run dev` ou `npm run build` pour la version de production

### Flutter :

- `cd flutter`
- `flutter pub get`
- `flutter run`
