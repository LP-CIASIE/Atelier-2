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


/** ========================
 * Création des routes
 * ====================== */
require dirname(__DIR__, 1) . '/src/routes/routes.php';


/** ========================
 * Lancement de l'application
 * ====================== */
$app->run();
