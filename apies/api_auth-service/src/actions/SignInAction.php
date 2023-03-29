<?php

namespace atelier\auth\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


use atelier\auth\services\utils\FormatterAPI as FormatterAPI;
use atelier\auth\services\TokenService as TokenService;
use atelier\auth\services\UserService;

final class SignInAction
{
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        $body = $rq->getBody();
        $body = json_decode($body, true);

        $userService = new UserService();
        $user = $userService->login($body);

        $tokenService = new TokenService();
        $tokenJWT = $tokenService->generateToken($user);

        $data = [
            'access-token' => $tokenJWT['access'],
            'refresh-token' => $tokenJWT['refresh_token'],
        ];

        return FormatterAPI::formatResponse($rq, $rs, $data, 200); // 200 = Created
    }
}
