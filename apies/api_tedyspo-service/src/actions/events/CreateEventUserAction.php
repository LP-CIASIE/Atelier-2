<?php

namespace atelier\tedyspo\actions\events;

use atelier\tedyspo\services\utils\FormatterAPI;
use atelier\tedyspo\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateEventUserAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $InvitationService = $this->container->get('service.invitation');

    $id_user = $args['id_user'];
    $id_event = $args['id_event'];

    $InvitationService->createEventUser($id_user, $id_event);

    return FormatterAPI::formatResponse($request, $response, [], 204);
  }
}
