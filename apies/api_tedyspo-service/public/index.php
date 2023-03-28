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


/**
 * API Basic Route
 */
$app->get('/', atelier\tedyspo\actions\HomeAction::class);

/** ======================
 *    Collection Users
 * ===================== */
$app->group('/users', function () use ($app) {
  // GET
  $app->get('[/]', atelier\tedyspo\actions\users\GetUsersAction::class);
  $app->get('/{id_user}[/]', atelier\tedyspo\actions\users\GetUserAction::class);

  // POST
  $app->post('[/]', atelier\tedyspo\actions\users\CreateUserAction::class);

  // PUT
  $app->put('/{id_user}[/]', atelier\tedyspo\actions\users\UpdateUserAction::class);

  // DELETE
  $app->delete('/{id_user}[/]', atelier\tedyspo\actions\users\DeleteUserAction::class);

  $app->group('/{id_user}', function () use ($app) {
    /** ===============================
     *   Collection Share Evenements
     * ============================= */
    $app->group('/events', function () use ($app) {
      // GET
      $app->get('/{id_event}[/]', atelier\tedyspo\actions\events\GetEventUserAction::class);

      // POST
      $app->post('/{id_event}[/]', atelier\tedyspo\actions\events\CreateUserEventAction::class);

      // PUT
      $app->put('/{id_event}[/]', atelier\tedyspo\actions\events\UpdateUserEventAction::class);

      // DELETE
      $app->delete('/{id_event}[/]', atelier\tedyspo\actions\events\DeleteUserEventAction::class);
    });
  });
});


/** =========================
 *    Collection Evenements
 * ======================= */
$app->group('/events', function () use ($app) {
  // GET
  $app->get('[/]', atelier\tedyspo\actions\events\GetEventsAction::class);
  $app->get('/{id_event}[/]', atelier\tedyspo\actions\events\GetEventAction::class);

  // POST
  $app->post('[/]', atelier\tedyspo\actions\events\CreateEventAction::class);

  // PUT
  $app->put('/{id_event}[/]', atelier\tedyspo\actions\events\UpdateEventAction::class);

  // DELETE
  $app->delete('/{id_event}[/]', atelier\tedyspo\actions\events\DeleteEventAction::class);

  $app->group('/{id_event}', function () use ($app) {
    /** ===============================
     *   Collection Share Evenements
     * ============================= */
    $app->group('/users', function () use ($app) {
      // GET
      $app->get('[/]', atelier\tedyspo\actions\events\GetEventUsersAction::class);
      $app->get('/{id_user}[/]', atelier\tedyspo\actions\events\GetEventUserAction::class);

      // POST
      $app->post('/{id_user}[/]', atelier\tedyspo\actions\events\CreateUserEventAction::class);

      // PUT
      $app->put('/{id_user}[/]', atelier\tedyspo\actions\events\UpdateUserEventAction::class);

      // DELETE
      $app->delete('/{id_user}[/]', atelier\tedyspo\actions\events\DeleteUserEventAction::class);
    });

    /** ============================
     *    Collection Commentaires
     * ========================== */
    $app->group('/comments', function () use ($app) {
      // GET
      $app->get('[/]', atelier\tedyspo\actions\comments\GetCommentsAction::class);
      $app->get('/{id_comment}[/]', atelier\tedyspo\actions\comments\GetCommentAction::class);

      // POST
      $app->post('[/]', atelier\tedyspo\actions\comments\CreateCommentAction::class);

      // PUT
      $app->put('/{id_comment}[/]', atelier\tedyspo\actions\comments\UpdateCommentAction::class);

      // DELETE
      $app->delete('/{id_comment}[/]', atelier\tedyspo\actions\comments\DeleteCommentAction::class);
    });

    /** ============================
     *    Collection Links
     * ========================== */
    $app->group('/links', function () use ($app) {
      // GET
      $app->get('[/]', atelier\tedyspo\actions\links\GetLinksAction::class);
      $app->get('/{id_link}[/]', atelier\tedyspo\actions\links\GetLinkAction::class);

      // POST
      $app->post('[/]', atelier\tedyspo\actions\links\CreateLinkAction::class);

      // PUT
      $app->put('/{id_link}[/]', atelier\tedyspo\actions\links\UpdateLinkAction::class);

      // DELETE
      $app->delete('/{id_link}[/]', atelier\tedyspo\actions\links\DeleteLinkAction::class);
    });

    /** ============================
     *    Collection locations
     * ========================== */
    $app->group('/locations', function () use ($app) {
      // GET
      $app->get('[/]', atelier\tedyspo\actions\locations\GetLocationsAction::class);
      $app->get('/{id_location}[/]', atelier\tedyspo\actions\locations\GetLocationAction::class);

      // POST
      $app->post('[/]', atelier\tedyspo\actions\locations\CreateLocationAction::class);

      // PUT
      $app->put('/{id_location}[/]', atelier\tedyspo\actions\locations\UpdateLocationAction::class);

      // DELETE
      $app->delete('/{id_location}[/]', atelier\tedyspo\actions\locations\DeleteLocationAction::class);
    });

    /** ============================
     *    Collection Additional Events
     * ========================== */
    $app->group('/events', function () use ($app) {
      // GET
      $app->get('[/]', atelier\tedyspo\actions\events\GetAdditionalsEventsAction::class);

      // POST
      $app->post('[/]', atelier\tedyspo\actions\events\CreateAdditionalEventAction::class);

      // PUT
      $app->put('/{id_additional_event}[/]', atelier\tedyspo\actions\events\UpdateAdditionalEventAction::class);
    });
  });
});

/** ============================
 *    Collection Medias
 * ========================== */
$app->group('/comments/{id_comment}/medias', function () use ($app) {
  // GET
  $app->get('[/]', atelier\tedyspo\actions\comments\GetCommentMediasAction::class);
  $app->get('/{id_media}[/]', atelier\tedyspo\actions\comments\GetCommentMediaAction::class);

  // POST
  $app->post('[/]', atelier\tedyspo\actions\comments\CreateCommentMediaAction::class);

  // PUT
  $app->put('/{id_media}[/]', atelier\tedyspo\actions\comments\UpdateCommentMediaAction::class);

  // DELETE
  $app->delete('/{id_media}[/]', atelier\tedyspo\actions\comments\DeleteCommentMediaAction::class);
});



$app->run();
