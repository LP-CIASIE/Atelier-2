<?php

namespace atelier\auth\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


use atelier\auth\services\utils\FormatterAPI as FormatterAPI;
use atelier\auth\services\TokenService as TokenService;
use atelier\auth\services\UserService;

final class SignInAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $body = $request->getBody();
            $body = json_decode($body, true);
        } catch (\Exception $e) {
            throw new \Exception('Aucune donnée reçu', 400);
        }

        $userService = new UserService();
        $user = $userService->login($body);

        $tokenService = new TokenService();
        $tokenJWT = $tokenService->generateToken($user);

        $data = [
            'access-token' => $tokenJWT['access'],
            'refresh-token' => $tokenJWT['refresh_token'],
        ];
        return FormatterAPI::formatResponse($request, $response, $data, 201); // 201 = Created
    }
}
