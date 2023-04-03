<?php

namespace atelier\tedyspo\actions\events;

use atelier\tedyspo\services\utils\FormatterAPI;
use atelier\tedyspo\services\utils\FormatterObject;
use atelier\tedyspo\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetEventUserAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $invitationService = $this->container->get('service.invitation');

    $id_user = $args['id_user'];
    $id_event = $args['id_event'];

    $eventUser = $invitationService->getEventUser($id_user, $id_event);

    $eventUser = FormatterObject::EventUser($eventUser);

    $data = [
      'eventUser' => $eventUser,
    ];

    return FormatterAPI::formatResponse($request, $response, $data, 200);
  }
}
