<?php
echo "===============\n";
echo "Script Started\n";
echo "===============\n";

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


// Generate users
$users = [];

echo "Generating users...\n";
for ($i = 0; $i < 400; $i++) {
  $id = $faker->uuid;
  $email = $faker->email;

  if (UserAuth::where('email', $email)->first()) {
    continue;
  }

  $userAuth = new UserAuth();
  $userAuth->id_user = $id;
  $userAuth->email = $email;
  $userAuth->password = password_hash('Tedyspo!', PASSWORD_BCRYPT, ['cost' => 12]);
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
echo "Users generated\n";

echo "-------------------------\n";

echo "Generating events...\n";
// Generate 2 - 3 events per user CONTRIBUTOR
$events = [];
foreach ($users as $user) {
  $nbEvents = rand(3, 5);
  for ($i = 0; $i < $nbEvents; $i++) {
    $id = $faker->uuid;

    $event = new Event();
    // Convert to string $id
    $event->id_event = $id;
    $event->title = $faker->sentence(3);
    $event->description = $faker->paragraph(3);
    $event->date = $faker->dateTimeBetween('-1 years', '+1 years');
    $event->is_public = 0;

    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $event->code_share = substr(str_shuffle($alphabet), 0, 5);

    $event->save();

    $comment = $faker->paragraph(6);

    $event->users()->attach($user['id_user'], ['is_organisator' => 1, 'state' => 'accepted', 'is_here' => rand(0, 1), 'comment' => $comment]);

    $events[] = [
      'id_event' => $id,
      'id_user' => $user['id_user'],
    ];
  }
}
echo "Events generated\n";

echo "-------------------------\n";

// Generate 2 - 3 participants per event
echo "Generating participants...\n";
$state = ['accepted', 'refused', 'pending'];
$commentList = ["Je serais là les amis !", "", ":D", "Vive les événements !", "Faites moi de la place :3", "J'y serais", "J'amènerais aussi des amis"];
foreach ($events as $event) {
  $nbParticipants = rand(2, 15);
  for ($i = 0; $i < $nbParticipants; $i++) {
    $participant = $users[rand(0, count($users) - 1)];
    while ($participant['id_user'] == $event['id_user']) {
      $participant = $users[rand(0, count($users) - 1)];
    }

    $stateRand = $state[rand(0, 2)];
    if ($stateRand == 'refused') {
      $comment = 'Désolé, je ne peux pas participer à cet événement.';
    } elseif ($stateRand == 'pending') {
      $comment = 'En attente de validation par l\'utilisateur';
    } elseif ($stateRand == 'accepted') {
      $comment = $commentList[rand(0, count($commentList) - 1)];
    }

    $isHere = ($stateRand == 'accepted') ? rand(0, 1) : 0;

    $event = Event::find($event['id_event']);
    $event->users()->attach($participant['id_user'], ['is_organisator' => 0, 'state' => $stateRand, 'is_here' => $isHere, 'comment' => $comment]);
  }
}
echo "Participants generated\n";

echo "-------------------------\n";

echo "Generating comments...\n";
// Generate 2 - 5 comments per event 
$comments = [];
foreach ($events as $event) {
  $nbComments = rand(0, 100);
  $participants = Event::find($event['id_event'])->users()->get()->toArray();
  for ($i = 0; $i < $nbComments; $i++) {
    $id = $faker->uuid;

    $comment = new Comment();
    $comment->id_comment = $id;
    $comment->comment = $faker->paragraph(3);
    $comment->id_user = $participants[rand(0, count($participants) - 1)]['id_user'];
    $comment->id_event = $event['id_event'];
    $comment->save();

    $comments[] = [
      'id_comment' => $id,
      'id_user' => $event['id_user'],
      'id_event' => $event['id_event'],
    ];
  }
}
echo "Comments generated\n";

echo "-------------------------\n";

echo "Generating locations...\n";
// Generate 2 - 5 locations per event
$locations = [];
foreach ($events as $event) {
  $nbLocations = rand(2, 10);
  for ($i = 0; $i < $nbLocations; $i++) {
    $id = $faker->uuid;

    $location = new Location();
    $location->id_location = $id;
    $location->name = $faker->sentence(3);
    $location->lat = $faker->latitude(43.0000, 49.0000);
    $location->long = $faker->longitude(-1.0000, 7.0000);

    if ($i == 0) {
      $location->is_related = 0;
    } else {
      $location->is_related = 1;
    }

    $location->id_event = $event['id_event'];
    $location->save();

    $locations[] = [
      'id_location' => $id,
      'id_event' => $event['id_event'],
    ];
  }
}
echo "Locations generated\n";

echo "-------------------------\n";

echo "Generating medias...\n";
// Generate 1 media every 4 comments
$medias = [];
foreach ($comments as $comment) {
  $nbMedias = rand(1, 4);
  if ($nbMedias == 4) {
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
echo "Medias generated\n";

echo "-------------------------\n";

echo "Generating links...\n";
// Generate 2 - 5 links per event
$links = [];
foreach ($events as $event) {
  $nbLinks = rand(0, 5);
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
echo "Links generated\n";

echo "===============\n";
echo "Script finished\n";
echo "===============\n";
