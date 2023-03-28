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


/** ======================
 *    Collection Users
 * ===================== */
$app->group('/users', function () use ($app) {
  // GET
  $app->get('[/]', atelier\gateway\actions\users\GetUsersAction::class)->add(new TokenMiddleware($app->getContainer()));
  $app->get('/{id_user}[/]', atelier\gateway\actions\users\GetUserAction::class)->add(new TokenMiddleware($app->getContainer()));

  // POST
  $app->post('[/]', atelier\gateway\actions\users\CreateUserAction::class)->add(new TokenMiddleware($app->getContainer()));

  // PUT
  $app->put('/{id_user}[/]', atelier\gateway\actions\users\UpdateUserAction::class)->add(new TokenMiddleware($app->getContainer()));

  // DELETE
  $app->delete('/{id_user}[/]', atelier\gateway\actions\users\DeleteUserAction::class)->add(new TokenMiddleware($app->getContainer()));

  $app->group('/{id_user}', function () use ($app) {
    /** ===============================
     *   Collection Share Evenements
     * ============================= */
    $app->group('/events', function () use ($app) {
      // GET
      $app->get('/{id_event}[/]', atelier\gateway\actions\events\GetEventUserAction::class)->add(new TokenMiddleware($app->getContainer()));

      // POST
      $app->post('/{id_event}[/]', atelier\gateway\actions\events\CreateUserEventAction::class)->add(new TokenMiddleware($app->getContainer()));

      // PUT
      $app->put('/{id_event}[/]', atelier\gateway\actions\events\UpdateUserEventAction::class)->add(new TokenMiddleware($app->getContainer()));

      // DELETE
      $app->delete('/{id_event}[/]', atelier\gateway\actions\events\DeleteUserEventAction::class)->add(new TokenMiddleware($app->getContainer()));
    });
  });
});


/** =========================
 *    Collection Evenements
 * ======================= */
$app->group('/events', function () use ($app) {
  // GET
  $app->get('[/]', atelier\gateway\actions\events\GetEventsAction::class)->add(new TokenMiddleware($app->getContainer()));
  $app->get('/{id_event}[/]', atelier\gateway\actions\events\GetEventAction::class)->add(new TokenMiddleware($app->getContainer()));

  // POST
  $app->post('[/]', atelier\gateway\actions\events\CreateEventAction::class)->add(new TokenMiddleware($app->getContainer()));

  // PUT
  $app->put('/{id_event}[/]', atelier\gateway\actions\events\UpdateEventAction::class)->add(new TokenMiddleware($app->getContainer()));

  // DELETE
  $app->delete('/{id_event}[/]', atelier\gateway\actions\events\DeleteEventAction::class)->add(new TokenMiddleware($app->getContainer()));

  $app->group('/{id_event}', function () use ($app) {
    /** ===============================
     *   Collection Share Evenements
     * ============================= */
    $app->group('/users', function () use ($app) {
      // GET
      $app->get('[/]', atelier\gateway\actions\events\GetEventUsersAction::class)->add(new TokenMiddleware($app->getContainer()));
      $app->get('/{id_user}[/]', atelier\gateway\actions\events\GetEventUserAction::class)->add(new TokenMiddleware($app->getContainer()));

      // POST
      $app->post('/{id_user}[/]', atelier\gateway\actions\events\CreateUserEventAction::class)->add(new TokenMiddleware($app->getContainer()));

      // PUT
      $app->put('/{id_user}[/]', atelier\gateway\actions\events\UpdateUserEventAction::class)->add(new TokenMiddleware($app->getContainer()));

      // DELETE
      $app->delete('/{id_user}[/]', atelier\gateway\actions\events\DeleteUserEventAction::class)->add(new TokenMiddleware($app->getContainer()));
    });

    /** ============================
     *    Collection Commentaires
     * ========================== */
    $app->group('/comments', function () use ($app) {
      // GET
      $app->get('[/]', atelier\gateway\actions\comments\GetCommentsAction::class)->add(new TokenMiddleware($app->getContainer()));
      $app->get('/{id_comment}[/]', atelier\gateway\actions\comments\GetCommentAction::class)->add(new TokenMiddleware($app->getContainer()));

      // POST
      $app->post('[/]', atelier\gateway\actions\comments\CreateCommentAction::class)->add(new TokenMiddleware($app->getContainer()));

      // PUT
      $app->put('/{id_comment}[/]', atelier\gateway\actions\comments\UpdateCommentAction::class)->add(new TokenMiddleware($app->getContainer()));

      // DELETE
      $app->delete('/{id_comment}[/]', atelier\gateway\actions\comments\DeleteCommentAction::class)->add(new TokenMiddleware($app->getContainer()));
    });

    /** ============================
     *    Collection Links
     * ========================== */
    $app->group('/links', function () use ($app) {
      // GET
      $app->get('[/]', atelier\gateway\actions\links\GetLinksAction::class)->add(new TokenMiddleware($app->getContainer()));
      $app->get('/{id_link}[/]', atelier\gateway\actions\links\GetLinkAction::class)->add(new TokenMiddleware($app->getContainer()));

      // POST
      $app->post('[/]', atelier\gateway\actions\links\CreateLinkAction::class)->add(new TokenMiddleware($app->getContainer()));

      // PUT
      $app->put('/{id_link}[/]', atelier\gateway\actions\links\UpdateLinkAction::class)->add(new TokenMiddleware($app->getContainer()));

      // DELETE
      $app->delete('/{id_link}[/]', atelier\gateway\actions\links\DeleteLinkAction::class)->add(new TokenMiddleware($app->getContainer()));
    });

    /** ============================
     *    Collection locations
     * ========================== */
    $app->group('/locations', function () use ($app) {
      // GET
      $app->get('[/]', atelier\gateway\actions\locations\GetLocationsAction::class)->add(new TokenMiddleware($app->getContainer()));
      $app->get('/{id_location}[/]', atelier\gateway\actions\locations\GetLocationAction::class)->add(new TokenMiddleware($app->getContainer()));

      // POST
      $app->post('[/]', atelier\gateway\actions\locations\CreateLocationAction::class)->add(new TokenMiddleware($app->getContainer()));

      // PUT
      $app->put('/{id_location}[/]', atelier\gateway\actions\locations\UpdateLocationAction::class)->add(new TokenMiddleware($app->getContainer()));

      // DELETE
      $app->delete('/{id_location}[/]', atelier\gateway\actions\locations\DeleteLocationAction::class)->add(new TokenMiddleware($app->getContainer()));
    });

    /** ============================
     *    Collection Additional Events
     * ========================== */
    $app->group('/events', function () use ($app) {
      // GET
      $app->get('[/]', atelier\gateway\actions\events\GetAdditionalsEventsAction::class)->add(new TokenMiddleware($app->getContainer()));

      // POST
      $app->post('[/]', atelier\gateway\actions\events\CreateAdditionalEventAction::class)->add(new TokenMiddleware($app->getContainer()));

      // PUT
      $app->put('/{id_additional_event}[/]', atelier\gateway\actions\events\UpdateAdditionalEventAction::class)->add(new TokenMiddleware($app->getContainer()));
    });
  });
});

/** ============================
 *    Collection Medias
 * ========================== */
$app->group('/comments/{id_comment}/medias', function () use ($app) {
  // GET
  $app->get('[/]', atelier\gateway\actions\comments\GetCommentMediasAction::class)->add(new TokenMiddleware($app->getContainer()));
  $app->get('/{id_media}[/]', atelier\gateway\actions\comments\GetCommentMediaAction::class)->add(new TokenMiddleware($app->getContainer()));

  // POST
  $app->post('[/]', atelier\gateway\actions\comments\CreateCommentMediaAction::class)->add(new TokenMiddleware($app->getContainer()));

  // PUT
  $app->put('/{id_media}[/]', atelier\gateway\actions\comments\UpdateCommentMediaAction::class)->add(new TokenMiddleware($app->getContainer()));

  // DELETE
  $app->delete('/{id_media}[/]', atelier\gateway\actions\comments\DeleteCommentMediaAction::class)->add(new TokenMiddleware($app->getContainer()));
});



/**
 * API Auth Service
 */
$app->post('/signin[/]', atelier\gateway\actions\auth\SignInAction::class);
$app->post('/signup[/]', atelier\gateway\actions\auth\SignUpAction::class);

$app->run();
