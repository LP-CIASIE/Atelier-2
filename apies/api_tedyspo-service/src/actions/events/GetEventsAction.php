<?php

namespace atelier\tedyspo\actions\events;

use atelier\auth\services\utils\FormatterAPI;
use atelier\tedyspo\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetEventsAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $eventService = $this->container->get('service.event');
    $events = $eventService->getEvents($args);
    $data = [
      'status' => 'success',
      'data' => $events
    ];
    return FormatterAPI::formatResponse($request, $response, $data);
  }
}
