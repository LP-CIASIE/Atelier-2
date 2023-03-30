<?php

namespace atelier\tedyspo\middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

use Psr\Container\ContainerInterface as Container;

class AccessEventMiddleware extends AbstractMiddleware
{
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
}
