<?php

namespace atelier\tedyspo\actions\events;

use atelier\auth\services\utils\FormatterAPI;
use atelier\tedyspo\actions\AbstractAction;
use atelier\tedyspo\services\utils\FormatterAPI as UtilsFormatterAPI;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetEventAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $eventService = $this->container->get('service.event');
    $events = $eventService->getEventById($args['id_event']);
    $data = [
      'status' => 'success',
      'data' => $events
    ];
    return FormatterAPI::formatResponse($request, $response, $data);
  }
}
