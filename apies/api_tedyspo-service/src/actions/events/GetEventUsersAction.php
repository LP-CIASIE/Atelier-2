<?php

namespace atelier\tedyspo\actions\events;

use atelier\tedyspo\services\utils\FormatterAPI;
use atelier\tedyspo\services\utils\FormatterObject;
use atelier\tedyspo\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetEventUsersAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {

    $InvitationService = $this->container->get('service.invitation');

    $id_event = $args['id_event'];

    $users = $InvitationService->getUsersByEvent($id_event);

    $users = FormatterObject::EventUsers($users);

    $data = [
      'users' => $users,
    ];

    return FormatterAPI::formatResponse($request, $response, $data, 200);
  }
}
