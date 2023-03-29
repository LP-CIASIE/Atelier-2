<?php

namespace atelier\auth\error\renderer;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class JSONErrorRenderer
{
  public function __invoke(Request $request, Response $response, \Throwable $exception): Response
  {
    $payload = [
      'error' => [
        'code' => $exception->getCode(),
        'message' => $exception->getMessage(),
      ],
    ];

    $response = $response->withStatus($exception->getCode());
    $response = $response->withHeader('Content-Type', 'application/json');
    $response->getBody()->write(json_encode($payload));

    return $response;
  }
}
