<?php

namespace atelier\gateway\actions\events;

use atelier\gateway\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateAdditionalEventAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    return $this->sendRequest($request, $response, '/events/' . $args['id_event'] . '/events', 'post');
  }
}
