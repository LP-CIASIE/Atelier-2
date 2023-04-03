<?php

namespace atelier\auth\actions;

use atelier\auth\services\UserService;
use atelier\auth\services\utils\FormatterAPI;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DeleteUserAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    // Changement des données de utilisateurs
    $userService = new UserService();
    $userService->deleteUserById($args['id_user']);

    return FormatterAPI::formatResponse($request, $response, [], 204);
  }
}
