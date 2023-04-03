<?php

namespace atelier\tedyspo\actions\events;

use atelier\tedyspo\services\utils\FormatterAPI;
use atelier\tedyspo\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UpdateUserEventAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $InvitationService = $this->container->get('service.invitation');

    $data = $this->parseBody($request);

    $id_event = $args['id_event'];
    $id_user = $args['id_user'];

    $eventUser = $InvitationService->updateStateUserEvent($id_event, $id_user, $data);


    // Si jamais on veut renvoyer l'objet modifié    
    $data = [
      'event' => $eventUser
    ];

    return FormatterAPI::formatResponse($request, $response, $data, 200);
  }
}
