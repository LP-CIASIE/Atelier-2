<?php

namespace atelier\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class HomeAction
{
  public function __invoke(
    Request $rq,
    Response $rs
  ): Response {
    $rs->getBody()->write('Hello, World!');
    return $rs;
  }
}
