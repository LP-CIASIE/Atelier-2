<?php

namespace atelier\tedyspo\middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;


class OwnerEventMiddleware extends AbstractMiddleware
{
  public function validateMiddleware(Request $request): bool
  {
    $JWTService = $this->container->get('service.jwt');
    $user = $JWTService->decodeDataOfJWT($request->getHeader('Authorization'));

    $event = $request->getAttribute('event');

    if ($event->getOwner() == $user['uid']) {
      return true;
    } else {
      return false;
    }
  }
}
