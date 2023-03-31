<?php

namespace atelier\tedyspo\actions\users;

use atelier\tedyspo\actions\AbstractAction;
use atelier\tedyspo\services\utils\FormatterAPI;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UpdateUserAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    // Récupération de l'utilisateur
    $user = $this->parseJWT($request);

    // Récupération des données du body
    $data = $this->parseBody($request);

    // Changement des données de utilisateurs
    $userService = $this->container->get('service.user');
    $userService->updateUser($user['uid'], $data);

    return FormatterAPI::formatResponse($request, $response, [], 204);
  }
}
