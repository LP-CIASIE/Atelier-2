<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;
use Illuminate\Database\Capsule\Manager as Capsule;

require_once dirname(__DIR__, 1) . '/vendor/autoload.php';

/** ========================
 * Création de Eloquent
 * ====================== */

$conf = parse_ini_file(dirname(__DIR__, 1) . '/conf/tedyspo.db.ini.dist');

$capsule = new Capsule;
$capsule->addConnection($conf);
$capsule->setAsGlobal();
$capsule->bootEloquent();

/** ========================
 * Création du container
 * ====================== */
$builder = new DI\ContainerBuilder();
$builder->addDefinitions(dirname(__DIR__, 1) . '/containers/settings.php');
$builder->addDefinitions(dirname(__DIR__, 1) . '/containers/service.php');
$builder->addDefinitions(dirname(__DIR__, 1) . '/containers/logger.php');
$container = $builder->build();

/** ========================
 * Création de $app Slim
 * ====================== */
$app = AppFactory::createFromContainer($container);
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

/** ========================
 * Création du ErrorMiddleware
 * ====================== */
$errorMiddleware = $app->addErrorMiddleware(
  $container->get('error.displayErrorDetails'),
  $container->get('error.logErrors'),
  $container->get('error.logErrorDetails'),
  $container->get('logger')
);


$errorMiddleware->getDefaultErrorHandler()->forceContentType('application/json');

/**
 * API Basic Route
 */

$app->get('/', atelier\tedyspo\actions\HomeAction::class)->setName('home');

/** ======================
 *    Collection Users
 * ===================== */
// GET

$app->get('/users[/]', atelier\tedyspo\actions\users\GetUsersAction::class)->setName('get_users');
$app->get('/users/{id_user}[/]', atelier\tedyspo\actions\users\GetUserAction::class)->setName('get_user');

// POST
$app->post('/signup[/]', atelier\tedyspo\actions\users\CreateUserAction::class)->setName('create_user');

// PUT
$app->put('/users[/]', atelier\tedyspo\actions\users\UpdateUserAction::class)->setName('update_user');

// DELETE
$app->delete('/users/{id_user}[/]', atelier\tedyspo\actions\users\DeleteUserAction::class)->setName('delete_user');

// Collection Share Evenements
// GET
$app->get('/users/{id_user}/events/{id_event}[/]', atelier\tedyspo\actions\events\GetEventUserAction::class)->setName('get_event_user');

// POST
$app->post('/users/{id_user}/events/{id_event}[/]', atelier\tedyspo\actions\events\CreateUserEventAction::class)->setName('create_user_event');

// PUT
$app->put('/users/{id_user}/events/{id_event}[/]', atelier\tedyspo\actions\events\UpdateUserEventAction::class)->setName('update_user_event');

// DELETE
$app->delete('/users/{id_user}/events/{id_event}[/]', atelier\tedyspo\actions\events\DeleteUserEventAction::class)->setName('delete_user_event');

/** =========================
 *    Collection Evenements
 * ======================= */

// Collection Additional Events
// GET

$app->get('/events/additionals[/]', atelier\tedyspo\actions\events\GetAdditionalsEventsAction::class)->setName('get_additional_events');

// POST
$app->post('/events/additionals[/]', atelier\tedyspo\actions\events\CreateAdditionalEventAction::class)->setName('create_additional_event');

// PUT
$app->put('/events/additionals/{id_additional_event}[/]', atelier\tedyspo\actions\events\UpdateAdditionalEventAction::class)->setName('update_additional_event');

// GET
$app->get('/events[/]', atelier\tedyspo\actions\events\GetEventsAction::class)->setName('get_events');
$app->get('/events/{id_event}[/]', atelier\tedyspo\actions\events\GetEventAction::class)->setName('get_event');

// POST
$app->post('/events[/]', atelier\tedyspo\actions\events\CreateEventAction::class)->setName('create_event');

// PUT
$app->put('/events/{id_event}[/]', atelier\tedyspo\actions\events\UpdateEventAction::class)->setName('update_event');

// DELETE
$app->delete('/events/{id_event}[/]', atelier\tedyspo\actions\events\DeleteEventAction::class)->setName('delete_event');

// Collection Share Evenements
// GET
$app->get('/events/{id_event}/users[/]', atelier\tedyspo\actions\events\GetEventUsersAction::class)->setName('get_event_users');
$app->get('/events/{id_event}/users/{id_user}[/]', atelier\tedyspo\actions\events\GetEventUserAction::class)->setName('get_event_user');

// POST
$app->post('/events/{id_event}/users/{id_user}[/]', atelier\tedyspo\actions\events\CreateUserEventAction::class)->setName('create_event_user');

/** ========================
 * Création des routes
 * ====================== */
require dirname(__DIR__, 1) . '/src/routes/routes.php';


/** ========================
 * Lancement de l'application
 * ====================== */
$app->run();
