<?php

namespace atelier\tedyspo\actions\events;

use atelier\tedyspo\services\utils\FormatterAPI;
use atelier\tedyspo\services\utils\FormatterObject;
use atelier\tedyspo\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetEventsUserAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $invitationService = $this->container->get('service.invitation');

    $id_user = $args['id_user'];

    $events = $invitationService->getEventsByUser($id_user);

    $events = FormatterObject::Events($events);

    $data = [
      'events' => $events,
    ];

    return FormatterAPI::formatResponse($request, $response, $data, 200);
  }
}
