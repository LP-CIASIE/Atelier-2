<?php

namespace atelier\gateway\actions\events;

use atelier\gateway\actions\AbstractAction;

class GetAdditionalsEventsAction extends AbstractAction
{
  public function __invoke($request, $response, $args)
  {
    $eventService = $this->container->get('service.event');

    return $response;
  }
}
