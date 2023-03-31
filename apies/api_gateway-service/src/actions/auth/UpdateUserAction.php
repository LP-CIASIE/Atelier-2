<?php

namespace atelier\gateway\actions\auth;

use atelier\gateway\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UpdateUserAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $this->sendRequest($request, $response, '/users', 'put', 'auth');
    return $this->sendRequest($request, $response, '/users', 'put', 'tedyspo');
  }
}
