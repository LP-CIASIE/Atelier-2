<?php

namespace atelier\tedyspo\actions\events;

use atelier\tedyspo\actions\AbstractAction;

class GetEventUserAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $eventService = $this->container->get('service.event');

    return $response;
  }
}
