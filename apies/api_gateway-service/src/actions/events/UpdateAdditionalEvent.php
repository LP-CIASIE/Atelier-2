<?php

namespace atelier\gateway\actions\events;

use atelier\gateway\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UpdateAdditionalEventAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    // Attention, ici on utilise deux fois le même $args['id_event']
    return $this->sendRequest($request, $response, '/events/' . $args['id_event'] . '/events/' . $args['sub_event'], 'put');
  }
}
