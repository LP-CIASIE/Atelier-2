<?php

namespace atelier\tedyspo\actions\events;

use atelier\tedyspo\services\utils\FormatterAPI;
use atelier\tedyspo\actions\AbstractAction;
use atelier\tedyspo\services\utils\FormatterObject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateEventAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $data = $this->parseBody($request);
    $user = $this->parseJWT($request);

    $eventService = $this->container->get('service.event');
    $event = $eventService->createEvent($user['uid'], $data);

    $data = [
      'event' => FormatterObject::Event($event)
    ];

    return FormatterAPI::formatResponse($request, $response, $data, 201);
  }
}
