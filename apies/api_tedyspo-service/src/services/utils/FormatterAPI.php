<?php

namespace atelier\tedyspo\services\utils;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class FormatterAPI
{
  public static function formatResponse(Request $request, Response $response, $data, $status = 200, $message = null): Response
  {
    $response = $response->withStatus($status);
    $response = $response->withHeader('Content-Type', 'application/json');

    $response->getBody()->write(json_encode($data));

    return $response;
  }
}
