<?php

namespace atelier\tedyspo\actions\events;

use atelier\tedyspo\services\utils\FormatterAPI;
use atelier\tedyspo\actions\AbstractAction;

class CreateEventAction extends AbstractAction
{
  public function __invoke($request, $response, $args)
  {
    $data = $request->getBody();
    $data = json_decode($data, true);

    $eventService = $this->container->get('service.event');
    $eventService->createEvent($data);

    return FormatterAPI::formatResponse($request, $response, [], 204);
  }
}
