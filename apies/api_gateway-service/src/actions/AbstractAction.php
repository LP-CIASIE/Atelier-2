<?php

namespace atelier\gateway\actions;

use Psr\Container\ContainerInterface;

use atelier\gateway\errors\exceptions\GuzzleException;
use GuzzleHttp\Exception\ClientException as GuzzleClientException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class AbstractAction
{
  protected ContainerInterface $container;

  public function __construct(ContainerInterface $container)
  {
    $this->container = $container;
  }

  public function sendRequest(Request $request, Response $response, $route, $method = 'get',  $args = '')
  {
    $client = $this->container->get('client.tedyspo.service');

    $authorization = $request->getHeader('Authorization');

    if (empty($authorization)) {
      throw new GuzzleException('Token manquant', 400);
    }

    try {
      if ($method === "post") {
        $responseHTTP = $client->post($route . $args, [
          'headers' => [
            'Authorization' => $request->getHeader('Authorization')[0],
          ],
          'body' => json_decode($request->getBody(), true),
        ]);
      } else if ($method === "get") {
        $responseHTTP = $client->get($route . $args, [
          'headers' => [
            'Authorization' => $request->getHeader('Authorization')[0],
          ],
        ]);
      } else if ($method === "put") {
        $responseHTTP = $client->put($route . $args, [
          'headers' => [
            'Authorization' => $request->getHeader('Authorization')[0],
          ],
          'body' => json_decode($request->getBody(), true),
        ]);
      } else if ($method === "delete") {
        $responseHTTP = $client->delete($route . $args, [
          'headers' => [
            'Authorization' => $request->getHeader('Authorization')[0],
          ],
          'body' => json_decode($request->getBody(), true),
        ]);
      }
    } catch (GuzzleClientException $e) {
      throw new GuzzleException('Erreur pendant l\'acheminant', $e->getCode());
    }


    $logger = $this->container->get('logger');
    $logger->info("{$method} | {$this->container->get('tedyspo.service.uri')}{$route} | {$responseHTTP->getStatusCode()}");

    return $responseHTTP;
  }
}
