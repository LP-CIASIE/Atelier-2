<?php

namespace atelier\tedyspo\actions\events;

use atelier\tedyspo\services\utils\FormatterAPI;
use atelier\tedyspo\actions\AbstractAction;
use atelier\tedyspo\services\utils\FormatterObject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetEventAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $eventService = $this->container->get('service.event');
    $event = $eventService->getEventById($args['id_event']);

    $data = [
      'event' => FormatterObject::Event($event)
    ];

    return FormatterAPI::formatResponse($request, $response, $data, 200);
  }
}
