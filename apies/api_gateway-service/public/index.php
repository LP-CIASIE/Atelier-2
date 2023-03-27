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



/**
 * API Auth Service
 */
$app->post('/signin[/]', atelier\gateway\actions\auth\SignInAction::class);


$app->run();
