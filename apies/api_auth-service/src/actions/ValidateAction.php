<?php

namespace atelier\auth\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use atelier\auth\services\utils\FormatterAPI as FormatterAPI;
use atelier\auth\services\TokenService as TokenService;
use Exception;

final class ValidateAction
{
  public function __invoke(Request $request, Response $response): Response
  {
    $token = $request->getHeader('Authorization');

    try {
      $tokenService = new TokenService();
      $tokenService->verifyToken($token);

      return FormatterAPI::formatResponse($request, $rs, [], 204); // 204 = No Content
    } catch (\Exception $e) {
      throw new Exception('Token invalide.', 401);
    }
  }
}
