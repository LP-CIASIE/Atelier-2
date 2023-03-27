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
$app->get('/', atelier\actions\HomeAction::class);


/** ======================
 * API Template Route
 * ===================== */
// GET


// POST


// PUT


// DELETE


/** ======================
 *    Collection Users
 * ===================== */
$app->group('/users', function () use ($app) {
  // GET
  $app->get('[/]', atelier\actions\user\GetUsersAction::class);
  $app->get('/{id}[/]', atelier\actions\user\GetUserAction::class);

  // POST
  $app->post('[/]', atelier\actions\user\CreateUserAction::class);

  // PUT
  $app->put('/{id}[/]', atelier\actions\user\UpdateUserAction::class);

  // DELETE
  $app->delete('/{id}[/]', atelier\actions\user\DeleteUserAction::class);

  $app->group('/{id}', function () use ($app) {
    /** ===============================
     *   Collection Share Evenements
     * ============================= */
    $app->group('/evenements', function () use ($app) {
      // GET
      $app->get('', atelier\actions\user\GetUserEventsAction::class);
      $app->get('/{id}', atelier\actions\user\GetUserEventAction::class);

      // POST
      $app->post('', atelier\actions\user\CreateUserEventAction::class);

      // PUT
      $app->put('/{id}', atelier\actions\user\UpdateUserEventAction::class);

      // DELETE
      $app->delete('/{id}', atelier\actions\user\DeleteUserEventAction::class);
    });
  });
});


/** =========================
 *    Collection Evenements
 * ======================= */
$app->group('/evenements', function () use ($app) {
  // GET
  $app->get('', atelier\actions\event\GetEventsAction::class);
  $app->get('/{id}', atelier\actions\event\GetEventAction::class);

  // POST
  $app->post('', atelier\actions\event\CreateEventAction::class);

  // PUT
  $app->put('/{id}', atelier\actions\event\UpdateEventAction::class);

  // DELETE
  $app->delete('/{id}', atelier\actions\event\DeleteEventAction::class);

  $app->group('/{id}', function () use ($app) {
    /** ===============================
     *   Collection Share Evenements
     * ============================= */
    $app->group('/users', function () use ($app) {
      // GET
      $app->get('', atelier\actions\event\GetEventParticipantsAction::class);
      $app->get('/{id}', atelier\actions\event\GetEventParticipantAction::class);

      // POST
      $app->post('', atelier\actions\event\CreateEventParticipantAction::class);

      // PUT
      $app->put('/{id}', atelier\actions\event\UpdateEventParticipantAction::class);

      // DELETE
      $app->delete('/{id}', atelier\actions\event\DeleteEventParticipantAction::class);
    });

    /** ============================
     *    Collection Commentaires
     * ========================== */
    $app->group('/commentaires', function () use ($app) {
      // GET
      $app->get('', atelier\actions\comment\GetCommentsAction::class);
      $app->get('/{id}', atelier\actions\comment\GetCommentAction::class);

      // POST
      $app->post('', atelier\actions\comment\CreateCommentAction::class);

      // PUT
      $app->put('/{id}', atelier\actions\comment\UpdateCommentAction::class);

      // DELETE
      $app->delete('/{id}', atelier\actions\comment\DeleteCommentAction::class);

      $app->group('/{id}/metdias', function () use ($app) {
        // GET
        $app->get('', atelier\actions\comment\GetCommentMediasAction::class);
        $app->get('/{id}', atelier\actions\comment\GetCommentMediaAction::class);

        // POST
        $app->post('', atelier\actions\comment\CreateCommentMediaAction::class);

        // PUT
        $app->put('/{id}', atelier\actions\comment\UpdateCommentMediaAction::class);

        // DELETE
        $app->delete('/{id}', atelier\actions\comment\DeleteCommentMediaAction::class);
      });
    });
  });
});





$app->run();
