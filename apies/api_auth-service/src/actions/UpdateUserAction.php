<?php

namespace atelier\auth\actions\users;

use atelier\auth\services\TokenService;
use atelier\auth\services\UserService;
use atelier\auth\services\utils\FormatterAPI;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UpdateUserAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    // Récupération de l'utilisateur
    $jwt = $request->getHeader('Authorization');

    $tokenService = new TokenService();
    $user = $tokenService->decodeDataOfJWT($jwt);

    // Récupération des données du body
    try {
      $body = $request->getBody();
      $body = json_decode($body, true);
    } catch (\Exception $e) {
      throw new \Exception('Aucune donnée reçu', 400);
    }

    // Changement des données de utilisateurs
    $userService = new UserService();
    $userService->updateUserById($user['uid'], $body);

    return FormatterAPI::formatResponse($request, $response, [], 204);
  }
}
