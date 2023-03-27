<?php

namespace atelier\gateway\middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

use Psr\Container\ContainerInterface as Container;

class TokenMiddleware
{
  private $container;

  public function __construct(Container $container)
  {
    $this->container = $container;
  }

  public function __invoke(Request $request, RequestHandler $handler): Response
  {

    if ($request->hasHeader('Authorization') && $this->validateToken($request->getHeader('Authorization')[0])) {

      $response = $handler->handle($request);
      return $response;
    } else {
      $response = new \Slim\Psr7\Response();
      $response->getBody()->write(json_encode([
        'type' => 'error',
        'error' => 401,
        'message' => 'Unauthorized'
      ]));
      return $response->withStatus(401);
    }
  }

  public function validateToken($token): bool
  {
    if (!$this->isBearer($token)) return false;

    $client = $this->container->get('client.auth.service');
    try {
      $client->post('/validate', [
        'headers' => [
          'Authorization' => $token
        ]
      ]);
    } catch (\Exception $e) {
      return false;
    }
    return true;
  }

  public function isBearer($token)
  {
    return preg_match('/^Bearer\s/', $token);
  }
}
