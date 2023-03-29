<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;
use Illuminate\Database\Capsule\Manager as Capsule;

require_once __DIR__ . '/../vendor/autoload.php';

/** ========================
 * Création de Eloquent
 * ====================== */
$conf = parse_ini_file(__DIR__ . '/../conf/api.db.ini.env');

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

$app->add($errorMiddleware);

/**
 * API Basic Route
 */
$app->get('/', atelier\tedyspo\actions\HomeAction::class);
/** ======================
 *    Collection Users
 * ===================== */
// GET
$app->get('/users[/]', atelier\tedyspo\actions\users\GetUsersAction::class);
$app->get('/users/{id_user}[/]', atelier\tedyspo\actions\users\GetUserAction::class);

// POST
$app->post('/users[/]', atelier\tedyspo\actions\users\CreateUserAction::class);

// PUT
$app->put('/users/{id_user}[/]', atelier\tedyspo\actions\users\UpdateUserAction::class);

// DELETE
$app->delete('/users/{id_user}[/]', atelier\tedyspo\actions\users\DeleteUserAction::class);

// Collection Share Evenements
// GET
$app->get('/users/{id_user}/events/{id_event}[/]', atelier\tedyspo\actions\events\GetEventUserAction::class);

// POST
$app->post('/users/{id_user}/events/{id_event}[/]', atelier\tedyspo\actions\events\CreateUserEventAction::class);

// PUT
$app->put('/users/{id_user}/events/{id_event}[/]', atelier\tedyspo\actions\events\UpdateUserEventAction::class);

// DELETE
$app->delete('/users/{id_user}/events/{id_event}[/]', atelier\tedyspo\actions\events\DeleteUserEventAction::class);

/** =========================
 *    Collection Evenements
 * ======================= */

// Collection Additional Events
// GET
$app->get('/events/additionals[/]', atelier\tedyspo\actions\events\GetAdditionalsEventsAction::class);

// POST
$app->post('/events/additionals[/]', atelier\tedyspo\actions\events\CreateAdditionalEventAction::class);

// PUT
$app->put('/events/additionals/{id_additional_event}[/]', atelier\tedyspo\actions\events\UpdateAdditionalEventAction::class);

// GET
$app->get('/events[/]', atelier\tedyspo\actions\events\GetEventsAction::class);
$app->get('/events/{id_event}[/]', atelier\tedyspo\actions\events\GetEventAction::class);

// POST
$app->post('/events[/]', atelier\tedyspo\actions\events\CreateEventAction::class);

// PUT
$app->put('/events/{id_event}[/]', atelier\tedyspo\actions\events\UpdateEventAction::class);

// DELETE
$app->delete('/events/{id_event}[/]', atelier\tedyspo\actions\events\DeleteEventAction::class);

// Collection Share Evenements
// GET
$app->get('/events/{id_event}/users[/]', atelier\tedyspo\actions\events\GetEventUsersAction::class);
$app->get('/events/{id_event}/users/{id_user}[/]', atelier\tedyspo\actions\events\GetEventUserAction::class);

// POST
$app->post('/events/{id_event}/users/{id_user}[/]', atelier\tedyspo\actions\events\CreateUserEventAction::class);

// PUT
$app->put('/events/{id_event}/users/{id_user}[/]', atelier\tedyspo\actions\events\UpdateUserEventAction::class);

// DELETE
$app->delete('/events/{id_event}/users/{id_user}[/]', atelier\tedyspo\actions\events\DeleteUserEventAction::class);

// Collection Commentaires
// GET
$app->get('/events/{id_event}/comments[/]', atelier\tedyspo\actions\comments\GetCommentsAction::class);
$app->get('/events/{id_event}/comments/{id_comment}[/]', atelier\tedyspo\actions\comments\GetCommentAction::class);

// POST
$app->post('/events/{id_event}/comments[/]', atelier\tedyspo\actions\comments\CreateCommentAction::class);

// PUT
$app->put('/events/{id_event}/comments/{id_comment}[/]', atelier\tedyspo\actions\comments\UpdateCommentAction::class);

// DELETE
$app->delete('/events/{id_event}/comments/{id_comment}[/]', atelier\tedyspo\actions\comments\DeleteCommentAction::class);

// Collection Links
// GET
$app->get('/events/{id_event}/links[/]', atelier\tedyspo\actions\links\GetLinksAction::class);
$app->get('/events/{id_event}/links/{id_link}[/]', atelier\tedyspo\actions\links\GetLinkAction::class);

// POST
$app->post('/events/{id_event}/links[/]', atelier\tedyspo\actions\links\CreateLinkAction::class);

// PUT
$app->put('/events/{id_event}/links/{id_link}[/]', atelier\tedyspo\actions\links\UpdateLinkAction::class);

// DELETE
$app->delete('/events/{id_event}/links/{id_link}[/]', atelier\tedyspo\actions\links\DeleteLinkAction::class);

// Collection locations
// GET
$app->get('/events/{id_event}/locations[/]', atelier\tedyspo\actions\locations\GetLocationsAction::class);
$app->get('/events/{id_event}/locations/{id_location}[/]', atelier\tedyspo\actions\locations\GetLocationAction::class);

// POST
$app->post('/events/{id_event}/locations[/]', atelier\tedyspo\actions\locations\CreateLocationAction::class);

// PUT
$app->put('/events/{id_event}/locations/{id_location}[/]', atelier\tedyspo\actions\locations\UpdateLocationAction::class);

// DELETE
$app->delete('/events/{id_event}/locations/{id_location}[/]', atelier\tedyspo\actions\locations\DeleteLocationAction::class);

/** ============================
 *    Collection Medias
 * ========================== */
// GET
$app->get('/comments/{id_comment}/medias[/]', atelier\tedyspo\actions\comments\GetCommentMediasAction::class);
$app->get('/comments/{id_comment}/medias/{id_media}[/]', atelier\tedyspo\actions\comments\GetCommentMediaAction::class);

// POST
$app->post('/comments/{id_comment}/medias[/]', atelier\tedyspo\actions\comments\CreateCommentMediaAction::class);

// PUT
$app->put('/comments/{id_comment}/medias/{id_media}[/]', atelier\tedyspo\actions\comments\UpdateCommentMediaAction::class);

// DELETE
$app->delete('/comments/{id_comment}/medias/{id_media}[/]', atelier\tedyspo\actions\comments\DeleteCommentMediaAction::class);

$app->run();
