

<?php

namespace atelier\gateway\actions;

use Psr\Container\ContainerInterface;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response as ResponseSlim;

abstract class AbstractAction
{
  protected ContainerInterface $container;

  public function __construct(ContainerInterface $container)
  {
    $this->container = $container;
  }

  public function sendRequest(Request $request, Response $response, $route, $method = 'get', $serveur = 'tedyspo')
  {

    // Récupère le token
    $token = $request->getHeader('Authorization')[0] ?? '';


    // récupère le client nécessaire
    if ($serveur === 'tedyspo') {
      $client = $this->container->get('client.tedyspo.service');
    } else if ($serveur === 'auth') {
      $client = $this->container->get('client.auth.service');
    }

    try {
      if ($method === "post") {
        $responseHTTP = $client->post($route, [
          'query' => $request->getQueryParams(),
          'headers' => [
            'Authorization' => $token,
            'Content-Type' => $this->container->get('content.type')
          ],
          'body' => json_encode($request->getParsedBody()),
        ]);
      } else if ($method === "get") {
        $responseHTTP = $client->get($route, [
          'query' => $request->getQueryParams(),
          'headers' => [
            'Authorization' => $token,
            'Content-Type' => $this->container->get('content.type')
          ],
        ]);
      } else if ($method === "put") {
        $responseHTTP = $client->put($route, [
          'query' => $request->getQueryParams(),
          'headers' => [
            'Authorization' => $token,
            'Content-Type' => $this->container->get('content.type')
          ],
          'body' => json_encode($request->getParsedBody()),
        ]);
      } else if ($method === "delete") {
        $responseHTTP = $client->delete($route, [
          'query' => $request->getQueryParams(),
          'headers' => [
            'Authorization' => $token,
            'Content-Type' => $this->container->get('content.type')
          ],
          'body' => json_encode($request->getParsedBody()),
        ]);
      }
    } catch (\GuzzleHttp\Exception\RequestException $e) {
      $response = new ResponseSlim();
      $jsonDecode = json_decode($e->getResponse()->getBody()->getContents(), true);
      // var_dump($jsonDecode);
      // die();
      $code = $jsonDecode['exception'][0]['code'] ?? 500;
      $code = $code === 0 ? 500 : $code;

      $exceptionData = [
        'code' => $code,
        'message' => $jsonDecode['exception'][0]['message'] ?? 'Erreur inconnue',
      ];

      $jsonExceptionData = json_encode($exceptionData, JSON_UNESCAPED_UNICODE);

      $response->getBody()->write($jsonExceptionData);
      $response = $response->withHeader('Content-Type', 'application/json');
      return $response->withStatus($exceptionData['code']);
    }

    $logger = $this->container->get('logger');
    $logger->info("{$method} | {$this->container->get('gateway.atelier.local')}{$route} | {$responseHTTP->getStatusCode()}");

    return $responseHTTP;
  }
}
