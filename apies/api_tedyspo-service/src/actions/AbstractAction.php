<?php

namespace atelier\tedyspo\actions;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class AbstractAction
{
  protected ContainerInterface $container;

  public function __construct(ContainerInterface $container)
  {
    $this->container = $container;
  }

  public function parseBody(Request $request): array
  {
    try {
      $body = $request->getBody();
      $body = json_decode($body, true);
    } catch (\Exception $e) {
      throw new \Exception('Aucune donnée reçu', 400);
    }

    return $body;
  }
}
