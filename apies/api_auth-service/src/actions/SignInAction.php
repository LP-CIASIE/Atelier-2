<?php

namespace atelier\auth\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


use atelier\auth\services\utils\FormatterAPI as FormatterAPI;
use atelier\auth\services\TokenService as TokenService;

final class SignInAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        if ($request->hasHeader('Authorization') == false) {
            $data = [
                'type' => 'error',
                'error' => 401,
                'message' => 'No authorization header present',
            ];
            return FormatterAPI::formatResponse($request, $response, $data, 401); // 401 = Bad Request
        }
        $header = $request->getHeader('Authorization')[0];
        $tokenstring = sscanf($header, "Basic %s");
        $usermail = base64_decode($tokenstring[0]);
        $user = list($usermail, $userpswd) = explode(':', $usermail);
        $tokenJWT = TokenService::createToken($user[0]);

        $data = [

            'access-token' => $tokenJWT['access'],
            'refresh-token' => $tokenJWT['refresh'],

        ];
        return FormatterAPI::formatResponse($request, $response, $data, 201); // 201 = Created
    }
}
