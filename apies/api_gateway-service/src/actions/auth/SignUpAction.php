<?php

namespace atelier\gateway\actions\auth;

use atelier\gateway\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class CreateUserAction extends AbstractAction
{
  public function __invoke(
    Request $request,
    Response $rs
  ): Response {
    $client = $this->container->get('client.auth.service');
    try {
      $responseHTTP = $client->post('/users', [
        'headers' => [
          'Authorization' => $request->getHeader('Authorization')[0],
        ],
      ]);
    } catch (\Exception $e) {
      $responseHTTP = new \Slim\Psr7\Response();
      $responseHTTP->getBody()->write(json_encode([
        'type' => 'error',
        'error' => 401,
        'message' => 'Unauthorized'
      ]));
      return $responseHTTP->withStatus(401);
    }

    $logger = $this->container->get('logger');
    $logger->info("SignUpAction | POST | {$this->container->get('auth.service.uri')}/signup | {$responseHTTP->getStatusCode()}");

    return $responseHTTP;
  }
}
