<?php

namespace atelier\tedyspo\actions\users;

use atelier\tedyspo\actions\AbstractAction;
use atelier\tedyspo\services\utils\FormatterAPI;
use atelier\tedyspo\services\utils\FormatterObject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateUserAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {

    $data = $request->getParsedBody();
    $data['id_user'] = $args['id_user'];

    $userService = $this->container->get('service.user');
    $user = $userService->createUser($data);


    $data = [
      'user' => FormatterObject::User($user)
    ];

    return FormatterAPI::formatResponse($request, $response, $data, 201); // 201 = Created
  }
}
