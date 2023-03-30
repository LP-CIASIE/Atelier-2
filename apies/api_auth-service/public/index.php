<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Slim\Factory\AppFactory as Appfactory;
use Illuminate\Database\Capsule\Manager as Capsule;
use Slim\Middleware\ErrorMiddleware;

require_once "../vendor/autoload.php";

/** ========================
 * Création de Eloquent
 * ====================== */
$conf = parse_ini_file(__DIR__ . '/../conf/auth.db.ini.env');

$capsule = new Capsule;
$capsule->addConnection($conf);
$capsule->setAsGlobal();
$capsule->bootEloquent();

/** ========================
 * Création de $app Slim
 * ====================== */
$app = AppFactory::create();

$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();

$errorMiddleware = new ErrorMiddleware(
  $app->getCallableResolver(),
  $app->getResponseFactory(),
  true, // affiche les messages d'erreur en mode développement
  true, // ne pas afficher les détails de l'erreur dans la réponse
  false // ne pas afficher les détails de l'erreur dans les journaux
);

$errorMiddleware->getDefaultErrorHandler()->forceContentType('application/json');

$app->add($errorMiddleware);

// Default route
$app->get('/', function (Request $request, Response $response, $args) {
  $response->getBody()->write("bonjour :)");

  return $response;
});

$app->post('/events[/]', atelier\auth\actions\Event::class);



$app->post('/signin[/]', atelier\auth\actions\SignInAction::class);
$app->post('/signup[/]', atelier\auth\actions\SignUpAction::class);
$app->put('/update[/]', atelier\auth\actions\SignUpAction::class);
$app->post('/validate[/]', atelier\auth\actions\ValidateAction::class);

$app->run();
