<?php

namespace atelier\tedyspo\actions\events;

use atelier\tedyspo\services\utils\FormatterAPI;
use atelier\tedyspo\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UpdateEventAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $data = $this->parseBody($request);

    $eventService = $this->container->get('service.event');
    $eventService->updateEvent($args['id_event'], $data);

    return FormatterAPI::formatResponse($request, $response, [], 204);
  }
}
