<?php

namespace atelier\tedyspo\actions\events;

use atelier\tedyspo\services\utils\FormatterAPI;
use atelier\tedyspo\services\utils\FormatterObject;
use atelier\tedyspo\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateUserEventAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $InvitationService = $this->container->get('service.invitation');

    $id_user = $args['id_user'];
    $id_event = $args['id_event'];

    $event = $InvitationService->createUserEvent($id_user, $id_event);

    $event = FormatterObject::Event($event);

    $data = [
      'event' => $event,
    ];

    return FormatterAPI::formatResponse($request, $response, $data, 204);
  }
}
