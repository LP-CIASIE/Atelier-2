<?php

namespace atelier\tedyspo\actions\events;

use atelier\tedyspo\services\utils\FormatterAPI;
use atelier\tedyspo\services\utils\FormatterObject;
use atelier\tedyspo\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetUsersEventAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {

    $invitationService = $this->container->get('service.invitation');

    $id_event = $args['id_event'];

    $usersEvent = $invitationService->getUsersEvent($id_event);

    $usersEvent = FormatterObject::EventUsers($usersEvent);

    $data = [
      'usersEvent' => $usersEvent,
    ];

    return FormatterAPI::formatResponse($request, $response, $data, 200);
  }
}
