<?php

namespace atelier\tedyspo\actions\events;

use atelier\auth\services\utils\FormatterAPI;
use atelier\tedyspo\actions\AbstractAction;

class UpdateEventAction extends AbstractAction
{
  public function __invoke($request, $response, $args)
  {
    $eventService = $this->container->get('service.event');
    $data = $request->getBody();
    $data = json_decode($data, true);
    $eventService->updateEvent($args['id_event'], $data);

    return $response->withStatus(200, 'Event updated');
  }
}
