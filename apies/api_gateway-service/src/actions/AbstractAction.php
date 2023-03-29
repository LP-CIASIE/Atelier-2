<?php

namespace atelier\gateway\actions;

use Psr\Container\ContainerInterface;

use atelier\gateway\errors\exceptions\GuzzleException;
use GuzzleHttp\Exception\ClientException as GuzzleClientException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Rules\Length;

abstract class AbstractAction
{
  protected ContainerInterface $container;

  public function __construct(ContainerInterface $container)
  {
    $this->container = $container;
  }

  public function sendRequest(Request $request, Response $response, $route, $method = 'get')
  {

    //Pas toujours ce client, il faut utiliser client.auth.service si c'est pour l'authentification
    // TODO : Faire une condition qui va chercher le bon client en fonction de la route // DONE : voir plus bas


    if ($route === '/signup' || $route === '/signin') {
      $client = $this->container->get('client.auth.service');
    } else {
      $client = $this->container->get('client.tedyspo.service');
    }

    $token = $request->getHeader('Authorization')[0] ?? '';

    $args = $request->getQueryParams() ?? [];


    if (count($args) > 0) {
      $getParams = '?';
      $i = 0;
      foreach ($request->getQueryParams() as $key => $value) {
        $getParams .= $key . '=' . $value;
        if ($i < count($args) - 1) {
          $getParams .= '&';
        }
      }
    } else {
      $getParams = '';
    }


    try {
      if ($method === "post") {
        $responseHTTP = $client->post($route . $getParams, [
          'headers' => [
            'Authorization' => $token,
          ],
          'body' => json_decode($request->getBody(), true),
        ]);
      } else if ($method === "get") {
        $responseHTTP = $client->get($route . $getParams, [
          'headers' => [
            'Authorization' => $token,
          ],
        ]);
      } else if ($method === "put") {
        $responseHTTP = $client->put($route . $getParams, [
          'headers' => [
            'Authorization' => $token,
          ],
          'body' => json_decode($request->getBody(), true),
        ]);
      } else if ($method === "delete") {
        $responseHTTP = $client->delete($route . $getParams, [
          'headers' => [
            'Authorization' => $token,
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
