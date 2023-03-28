<?php

namespace atelier\tedyspo\actions\events;

use atelier\tedyspo\actions\AbstractAction;

class CreateAdditionalEventAction extends AbstractAction
{
  public function __invoke($request, $response, $args)
  {
    $eventService = $this->container->get('service.event');

    return $response;
  }
}
