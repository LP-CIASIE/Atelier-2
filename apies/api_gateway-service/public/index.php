<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;
use atelier\gateway\middlewares\TokenMiddleware;



require_once __DIR__ . '/../vendor/autoload.php';

/** ========================
 * Création du container
 * ====================== */
$builder = new DI\ContainerBuilder();
$builder->addDefinitions(dirname(__DIR__, 1) . '/containers/settings.php');
$builder->addDefinitions(dirname(__DIR__, 1) . '/containers/guzzleLibrary.php');
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


$app->options('/{routes:.+}', function ($request, $response, $args) {
  return $response;
});

$app->add(function ($request, $handler) {
  $response = $handler->handle($request);
  return $response
    ->withHeader('Access-Control-Allow-Origin', '*')
    ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
    ->withHeader('Access-Control-Allow-Credentials', 'true')
    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});
/**
 * API Basic Route
 */
$app->get('/', atelier\gateway\actions\HomeAction::class);
/** ======================
 *    Collection Users
 * ===================== */
// GET
$app->get('/users[/]', atelier\gateway\actions\users\GetUsersAction::class);
$app->get('/users/{id_user}[/]', atelier\gateway\actions\users\GetUserAction::class);

// POST
//Création d'un utilisateur ????
$app->post('/users[/]', atelier\gateway\actions\users\CreateUserAction::class);

// PUT
$app->put('/users/{id_user}[/]', atelier\gateway\actions\users\UpdateUserAction::class);

// DELETE
$app->delete('/users/{id_user}[/]', atelier\gateway\actions\users\DeleteUserAction::class);

// Collection Share Evenements
// GET
$app->get('/users/{id_user}/events/{id_event}[/]', atelier\gateway\actions\events\GetEventUserAction::class);

// POST
$app->post('/users/{id_user}/events/{id_event}[/]', atelier\gateway\actions\events\CreateUserEventAction::class);

// PUT
$app->put('/users/{id_user}/events/{id_event}[/]', atelier\gateway\actions\events\UpdateUserEventAction::class);

// DELETE
$app->delete('/users/{id_user}/events/{id_event}[/]', atelier\gateway\actions\events\DeleteUserEventAction::class);

/** =========================
 *    Collection Evenements
 * ======================= */

// Collection Additional Events
// GET
$app->get('/events/additionals[/]', atelier\gateway\actions\events\GetAdditionalsEventsAction::class);

// POST
$app->post('/events/additionals[/]', atelier\gateway\actions\events\CreateAdditionalEventAction::class);

// PUT
$app->put('/events/additionals/{id_additional_event}[/]', atelier\gateway\actions\events\UpdateAdditionalEventAction::class);

// GET
$app->get('/events[/]', atelier\gateway\actions\events\GetEventsAction::class);
$app->get('/events/{id_event}[/]', atelier\gateway\actions\events\GetEventAction::class);

// POST
$app->post('/events[/]', atelier\gateway\actions\events\CreateEventAction::class);

// PUT
$app->put('/events/{id_event}[/]', atelier\gateway\actions\events\UpdateEventAction::class);

// DELETE
$app->delete('/events/{id_event}[/]', atelier\gateway\actions\events\DeleteEventAction::class);

// Collection Share Evenements
// GET
$app->get('/events/{id_event}/users[/]', atelier\gateway\actions\events\GetEventUsersAction::class);
$app->get('/events/{id_event}/users/{id_user}[/]', atelier\gateway\actions\events\GetEventUserAction::class);

// POST
$app->post('/events/{id_event}/users/{id_user}[/]', atelier\gateway\actions\events\CreateUserEventAction::class);

// PUT
$app->put('/events/{id_event}/users/{id_user}[/]', atelier\gateway\actions\events\UpdateUserEventAction::class);

// DELETE
$app->delete('/events/{id_event}/users/{id_user}[/]', atelier\gateway\actions\events\DeleteUserEventAction::class);

// Collection Commentaires
// GET
$app->get('/events/{id_event}/comments[/]', atelier\gateway\actions\comments\GetCommentsAction::class);
$app->get('/events/{id_event}/comments/{id_comment}[/]', atelier\gateway\actions\comments\GetCommentAction::class);

// POST
$app->post('/events/{id_event}/comments[/]', atelier\gateway\actions\comments\CreateCommentAction::class);

// PUT
$app->put('/events/{id_event}/comments/{id_comment}[/]', atelier\gateway\actions\comments\UpdateCommentAction::class);

// DELETE
$app->delete('/events/{id_event}/comments/{id_comment}[/]', atelier\gateway\actions\comments\DeleteCommentAction::class);

// Collection Links
// GET
$app->get('/events/{id_event}/links[/]', atelier\gateway\actions\links\GetLinksAction::class);
$app->get('/events/{id_event}/links/{id_link}[/]', atelier\gateway\actions\links\GetLinkAction::class);

// POST
$app->post('/events/{id_event}/links[/]', atelier\gateway\actions\links\CreateLinkAction::class);

// PUT
$app->put('/events/{id_event}/links/{id_link}[/]', atelier\gateway\actions\links\UpdateLinkAction::class);

// DELETE
$app->delete('/events/{id_event}/links/{id_link}[/]', atelier\gateway\actions\links\DeleteLinkAction::class);

// Collection locations
// GET
$app->get('/events/{id_event}/locations[/]', atelier\gateway\actions\locations\GetLocationsAction::class);
$app->get('/events/{id_event}/locations/{id_location}[/]', atelier\gateway\actions\locations\GetLocationAction::class);

// POST
$app->post('/events/{id_event}/locations[/]', atelier\gateway\actions\locations\CreateLocationAction::class);

// PUT
$app->put('/events/{id_event}/locations/{id_location}[/]', atelier\gateway\actions\locations\UpdateLocationAction::class);

// DELETE
$app->delete('/events/{id_event}/locations/{id_location}[/]', atelier\gateway\actions\locations\DeleteLocationAction::class);

/** ============================
 *    Collection Medias
 * ========================== */
// GET
$app->get('/comments/{id_comment}/medias[/]', atelier\gateway\actions\medias\GetCommentMediasAction::class);
$app->get('/comments/{id_comment}/medias/{id_media}[/]', atelier\gateway\actions\medias\GetCommentMediaAction::class);

// POST
$app->post('/comments/{id_comment}/medias[/]', atelier\gateway\actions\medias\CreateCommentMediaAction::class);

// PUT
$app->put('/comments/{id_comment}/medias/{id_media}[/]', atelier\gateway\actions\medias\UpdateCommentMediaAction::class);

// DELETE
$app->delete('/comments/{id_comment}/medias/{id_media}[/]', atelier\gateway\actions\medias\DeleteCommentMediaAction::class);




/**
 * API Auth Service
 */
$app->post('/signin[/]', atelier\gateway\actions\auth\SignInAction::class);
$app->post('/signup[/]', atelier\gateway\actions\auth\SignUpAction::class);

$app->run();
