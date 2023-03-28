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


/**
 * API Basic Route
 */
$app->get('/', atelier\gateway\actions\HomeAction::class);


/**
 * API Reunionou Service
 */

$app->group('/users', function () use ($app) {
  $app()->get('[/]', atelier\gateway\actions\users\GetUsersAction::class);
  $app()->post('[/]', atelier\gateway\actions\users\CreateUserAction::class);
  $app()->get('/{id_user}[/]', atelier\gateway\actions\users\GetUserAction::class);
  $app()->put('/{id_user}[/]', atelier\gateway\actions\users\UpdateUserAction::class);
  $app()->delete('/{id_user}[/]', atelier\gateway\actions\users\DeleteUserAction::class);
  $app->group('/{id_user}/events', function () use ($app) {
    $app()->post('/{id_event}[/]', atelier\gateway\actions\users\AddUserEventAction::class);
    $app()->get('/{id_event}[/]', atelier\gateway\actions\users\GetUsersEventAction::class);
    $app()->put('/{id_event}[/]', atelier\gateway\actions\users\UpdateUserEventAction::class);
    $app()->delete('/{id_event}[/]', atelier\gateway\actions\users\DeleteUserEventAction::class);
  });
});

$app->group('/events', function () use ($app) {
  $app()->get('[/]', atelier\gateway\actions\events\GetEventsAction::class);
  $app()->post('[/]', atelier\gateway\actions\events\CreateEventAction::class);
  $app()->get('/{id_event}[/]', atelier\gateway\actions\events\GetEventAction::class);
  $app()->put('/{id_event}[/]', atelier\gateway\actions\events\UpdateEventAction::class);
  $app()->delete('/{id_event}[/]', atelier\gateway\actions\events\DeleteEventAction::class);

  $app->group('/{id_event}/users', function () use ($app) {
    $app()->post('.{id_user}[/]', atelier\gateway\actions\events\AddEventUserAction::class);
    $app()->get('/{id_user}[/]', atelier\gateway\actions\events\GetEventUsersAction::class);
    $app()->put('/{id_user}[/]', atelier\gateway\actions\events\UpdateEventUserAction::class);
    $app()->delete('/{id_user}[/]', atelier\gateway\actions\events\DeleteEventUserAction::class);
  });

  $app->group('/{id_event}/comment', function () use ($app) {
    $app->get('[/]', atelier\gateway\actions\events\GetEventCommentsAction::class);
    $app->post('[/]', atelier\gateway\actions\events\CreateEventCommentAction::class);
    $app->get('/{id_comment}[/]', atelier\gateway\actions\events\GetEventCommentAction::class);
    $app->put('/{id_comment}[/]', atelier\gateway\actions\events\UpdateEventCommentAction::class);
    $app->delete('/{id_comment}[/]', atelier\gateway\actions\events\DeleteEventCommentAction::class);
  });

  $app->group('/{id_event}/links', function () use ($app) {
    $app->get('[/]', atelier\gateway\actions\events\GetEventLinkAction::class);
    $app->post('[/]', atelier\gateway\actions\events\CreateEventLinkAction::class);
    $app->get('/{id_link}[/]', atelier\gateway\actions\events\GetEventLinkAction::class);
    $app->put('/{id_link}[/]', atelier\gateway\actions\events\UpdateEventLinkAction::class);
    $app->delete('/{id_link}[/]', atelier\gateway\actions\events\DeleteEventLinkAction::class);
  });

  $app->group('/{id_event}/locations', function () use ($app) {
    $app->get('[/]', atelier\gateway\actions\events\GetEventlocationsAction::class);
    $app->post('[/]', atelier\gateway\actions\events\CreateEventlocationAction::class);
    $app->get('/{id_location}[/]', atelier\gateway\actions\events\GetEventlocationAction::class);
    $app->put('/{id_location}[/]', atelier\gateway\actions\events\UpdateEventlocationAction::class);
    $app->delete('/{id_location}[/]', atelier\gateway\actions\events\DeleteEventlocationAction::class);
  });

  $app->group('/{id_event}', function () use ($app) {
    $app->get('/events[/]', atelier\gateway\actions\events\GetAdditionalsEventsAction::class);
    $app->post('/events[/]', atelier\gateway\actions\events\CreateAdditionalEventAction::class);
    $app->put('/events/{id_additional_event}[/]', atelier\gateway\actions\events\UpdateAdditionalEventAction::class);
  });
});

$app->group('/comments', function () use ($app) {
  $app->get('/{id_comment}/medias[/]', atelier\gateway\actions\media\GetCommentsAction::class);
  $app->post('/{id_comment}/medias[/]', atelier\gateway\actions\media\CreateCommentAction::class);
  $app->get('/{id_comment}/medias/{id_media}[/]', atelier\gateway\actions\media\GetCommentAction::class);
  $app->put('/{id_comment}/medias/{id_media}[/]', atelier\gateway\actions\media\UpdateCommentAction::class);
  $app->delete('/{id_comment}/medias/{id_media}[/]', atelier\gateway\actions\media\DeleteCommentAction::class);
});

/**
 * API Auth Service
 */
$app->post('/auth[/]', atelier\gateway\actions\auth\SignInAction::class);
$app->delete('/auth[/]', atelier\gateway\actions\auth\SignOutAction::class);

$app->run();
