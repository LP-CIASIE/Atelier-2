<?php
require __DIR__ . '/vendor/autoload.php';

// Generate Fake Data for BDD

use atelier\fakedata\models\Comment;
use atelier\fakedata\models\Event;
use atelier\fakedata\models\Link;
use atelier\fakedata\models\Location;
use atelier\fakedata\models\Media;
use Faker\Factory;
use Illuminate\Database\Capsule\Manager as Capsule;

// Création de Eloquent BDD
$confTedyspo = parse_ini_file(__DIR__ . '/conf/tedyspo.db.ini');
$confAuth = parse_ini_file(__DIR__ . '/conf/auth.db.ini');

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

// Generate 200 users
$users = [];

for ($i = 0; $i < 1000; $i++) {
  $id = $faker->uuid;
  $email = $faker->email;
  $firstname = $faker->firstName;

  $userAuth = new UserAuth();
  $userAuth->id_user = $id;
  $userAuth->email = $email;
  $userAuth->password = password_hash($firstname, PASSWORD_BCRYPT, ['cost' => 12]);
  $userAuth->refresh_token = base64_encode(random_bytes(150));
  $userAuth->role = 'user';
  $userAuth->save();


  $userTedyspo = new UserTedyspo();
  $userTedyspo->id_user = $id;
  $userTedyspo->email = $email;
  $userTedyspo->firstname = $faker->firstName;
  $userTedyspo->lastname = ($i % 2 == 0) ? $faker->lastName : "";
  $userTedyspo->save();

  $users[] = [
    'id_user' => $id,
  ];
}

// Generate 2 - 5 events per user
$state = ['accepted', 'refused', 'pending'];
$events = [];
foreach ($users as $user) {
  $nbEvents = rand(2, 5);
  for ($i = 0; $i < $nbEvents; $i++) {
    $id = $faker->uuid;

    $event = new Event();
    // Convert to string $id
    $event->id_event = $id;
    $event->title = $faker->sentence(3);
    $event->description = $faker->paragraph(3);
    $event->date = $faker->dateTimeBetween('-1 years', '+1 years');
    $event->is_public = rand(0, 1);

    $event->save();

    $is_organisator = rand(0, 1);
    $stateRand = $state[rand(0, 2)];
    $comment = $faker->paragraph(6);

    $event->users()->attach($user['id_user'], ['is_organisator' => $is_organisator, 'state' => $stateRand, 'comment' => $comment]);

    $events[] = [
      'id_event' => $id,
      'id_user' => $user['id_user'],
    ];
  }
}

// Generate 2 - 5 comments per event
$comments = [];
foreach ($events as $event) {
  $nbComments = rand(2, 5);
  for ($i = 0; $i < $nbComments; $i++) {
    $id = $faker->uuid;

    $comment = new Comment();
    $comment->id_comment = $id;
    $comment->comment = $faker->paragraph(3);
    $comment->id_user = $event['id_user'];
    $comment->id_event = $event['id_event'];
    $comment->save();

    $comments[] = [
      'id_comment' => $id,
      'id_user' => $event['id_user'],
      'id_event' => $event['id_event'],
    ];
  }
}

// Generate 2 - 5 locations per event
$locations = [];
foreach ($events as $event) {
  $nbLocations = rand(2, 5);
  for ($i = 0; $i < $nbLocations; $i++) {
    $id = $faker->uuid;

    $location = new Location();
    $location->id_location = $id;
    $location->name = $faker->sentence(3);
    $location->lat = $faker->latitude;
    $location->long = $faker->longitude;
    $location->is_related = $faker->boolean;
    $location->id_event = $event['id_event'];
    $location->save();

    $locations[] = [
      'id_location' => $id,
      'id_event' => $event['id_event'],
    ];
  }
}

// Generate 1 media every 2 comments
$medias = [];
foreach ($comments as $comment) {
  $nbMedias = rand(0, 1);
  if ($nbMedias == 1) {
    $id = $faker->uuid;

    $media = new Media();
    $media->id_media = $id;
    $media->type = 'image';
    $media->path = $faker->imageUrl();
    $media->id_comment = $comment['id_comment'];
    $media->save();

    $medias[] = [
      'id_media' => $id,
      'id_comment' => $comment['id_comment'],
    ];
  }
}

// Generate 2 - 5 links per event
$links = [];
foreach ($events as $event) {
  $nbLinks = rand(2, 5);
  for ($i = 0; $i < $nbLinks; $i++) {
    $id = $faker->uuid;

    $link = new Link();
    $link->id_link = $id;
    $link->title = $faker->sentence(3);
    $link->link = $faker->url;
    $link->id_event = $event['id_event'];
    $link->save();

    $links[] = [
      'id_link' => $id,
      'id_event' => $event['id_event'],
    ];
  }
}
