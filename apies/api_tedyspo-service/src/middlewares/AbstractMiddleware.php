<?php

namespace atelier\tedyspo\middlewares;

use Exception;
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

  abstract public function ErrorMiddleware(): \Throwable;
}
