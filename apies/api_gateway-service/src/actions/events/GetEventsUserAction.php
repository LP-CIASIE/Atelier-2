<?php

namespace atelier\gateway\actions\events;

use atelier\gateway\actions\AbstractAction;

class GetEventsUserAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $eventService = $this->container->get('service.event');
  }
}
