<?php

namespace atelier\tedyspo\actions\events;

use atelier\tedyspo\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DeleteEventAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $eventService = $this->container->get('service.event');

    return $response;
  }
}
