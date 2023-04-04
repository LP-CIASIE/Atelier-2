<?php

namespace atelier\tedyspo\middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

use Psr\Container\ContainerInterface as Container;

abstract class AbstractMiddleware
{
  protected $container;

  public function __construct(Container $container)
  {
    $this->container = $container;
  }

  public function __invoke(Request $request, RequestHandler $handler): Response
  {

    if ($this->validateMiddleware($request)) {

      $response = $handler->handle($request);
      return $response;
    } else {
      $this->ErrorMiddleware();
    }
  }

  abstract public function validateMiddleware(Request $request): bool;

  // Format : 
  // [
  //   'code' => 404,
  //   'message' => 'Message de l\erreur'
  // ]
  abstract public function ErrorMiddleware(): array;
}
