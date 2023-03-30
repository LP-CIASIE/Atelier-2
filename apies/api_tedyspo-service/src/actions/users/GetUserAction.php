<?php

namespace atelier\tedyspo\actions\users;

use atelier\tedyspo\actions\AbstractAction;
use atelier\tedyspo\services\utils\FormatterAPI;
use atelier\tedyspo\services\utils\FormatterObject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetUserAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $userService = $this->container->get('service.user');

    $user = $userService->getUserById($args['id_user']);

    $data = [
      'user' => FormatterObject::User($user)
    ];

    return FormatterAPI::formatResponse($request, $response, $data, 200);
  }
}
