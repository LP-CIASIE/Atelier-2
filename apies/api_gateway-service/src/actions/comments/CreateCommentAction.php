<?php

namespace atelier\gateway\actions\comments;

use atelier\gateway\actions\AbstractAction;

class CreateCommentAction extends AbstractAction
{
  public function __invoke($request, $response, $args)
  {
    $client = $this->container->get('client.tedyspo.service');
    try {
      $responseHTTP = $client->post('/comments', [
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
    $logger->info("CreateCommentAction | POST | {$this->container->get('tedyspo.service.uri')}/comments | {$responseHTTP->getStatusCode()}");

    return $responseHTTP;
  }
}
