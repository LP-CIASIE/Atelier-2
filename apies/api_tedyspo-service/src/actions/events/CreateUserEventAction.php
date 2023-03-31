<?php

namespace atelier\tedyspo\actions\events;

use atelier\tedyspo\services\utils\FormatterAPI;
use atelier\tedyspo\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateUserEventAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $eventService = $this->container->get('service.event');

    $id_user = $args['id_user'];
    $id_event = $args['id_event'];

    $response = $eventService->createUserEvent($id_user, $id_event);

    return FormatterAPI::formatResponse($request, $response, [], 204);
  }
}
