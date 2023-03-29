<?php
require __DIR__ . '/vendor/autoload.php';

// Generate Fake Data for BDD
use Faker\Factory;
use Illuminate\Database\Capsule\Manager as Capsule;

// Création de Eloquent BDD
$confTedyspo = parse_ini_file(__DIR__ . '/conf/tedyspo.db.ini.env');
$confAuth = parse_ini_file(__DIR__ . '/conf/auth.db.ini.env');

$capsule = new Capsule;
$capsule->addConnection($confTedyspo, 'tedyspo_db');
$capsule->addConnection($confAuth, 'auth_db');
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Import models
use atelier\fakedata\models\UserAuth;
use atelier\fakedata\models\UserTedyspo;


// Create a Faker instance
$faker = Factory::create('fr_FR');

