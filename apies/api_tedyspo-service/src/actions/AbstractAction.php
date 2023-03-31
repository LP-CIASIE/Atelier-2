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

      if ($body === null) {
        throw new \Exception('Aucune donnée reçu', 400);
      }
    } catch (\Exception $e) {
      throw new \Exception('Aucune donnée reçu', 400);
    }

    return $body;
  }

  public function parseJWT(Request $request): array
  {
    $jwt = $request->getHeader('Authorization');

    $jwtService = $this->container->get('service.jwt');
    $user = $jwtService->decodeDataOfJWT($jwt);

    return $user;
  }
}
