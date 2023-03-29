<?php

namespace atelier\tedyspo\actions\events;

use atelier\tedyspo\actions\AbstractAction;
use atelier\tedyspo\services\utils\FormatterAPI;

class GetEventsAction extends AbstractAction
{
  public function __invoke($request, $response, $args)
  {
    $eventService = $this->container->get('service.event');
    $events = $eventService->getEvents();
    $data = [
      'status' => 'success',
      'data' => $events
    ];
    return FormatterAPI::formatResponse($request, $response, $data);

    return $response;
  }
}
