<?php

namespace atelier\tedyspo\actions\events;

use atelier\tedyspo\services\utils\FormatterAPI;
use atelier\tedyspo\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateEventAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $data = $request->getBody();
    $data = json_decode($data, true);

    $eventService = $this->container->get('service.event');
    $eventService->createEvent($data);
    $data = [
      'message' => 'Event created successfully',
      'data' => $data
    ];

    return FormatterAPI::formatResponse($request, $response, $data, 201);
  }
}
