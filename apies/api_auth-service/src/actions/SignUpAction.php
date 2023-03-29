<?php

namespace atelier\auth\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


use atelier\auth\services\utils\FormatterAPI as FormatterAPI;
use atelier\auth\services\UserService;

final class SignUpAction
{
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        $body = $rq->getBody();
        $body = json_decode($body, true);

        $userService = new UserService();

        $userService->createUser($body);

        return FormatterAPI::formatResponse($rq, $rs, [], 204); // 204 = No Content
    }
}
