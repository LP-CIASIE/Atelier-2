<?php

namespace atelier\tedyspo\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class HomeAction
{
  public function __invoke(
    Request $request,
    Response $rs
  ): Response {
    $rs->getBody()->write('Hello, World!');
    return $rs;
  }
}
