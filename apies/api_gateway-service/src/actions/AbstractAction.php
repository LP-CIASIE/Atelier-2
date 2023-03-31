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

  public function sendRequest(Request $request, Response $response, $route, $method = 'get')
  {

    // Récupère le token
    $token = $request->getHeader('Authorization')[0] ?? '';


    // récupère les clients Auth et Tedyspo
    $clientAuth = $this->container->get('client.auth.service');
    $clientTedyspo = $this->container->get('client.tedyspo.service');

    if ($route === '/signup') {
      try {
        $responseHTTP = $clientAuth->post($route, [
          'query' => $request->getQueryParams(),
          'headers' => [
            'Authorization' => $token,
            'Content-Type' => $this->container->get('content.type')
          ],
          'body' => json_encode($request->getParsedBody()),
        ]);
        $responseHTTP = $clientTedyspo->post($route, [
          'query' => $request->getQueryParams(),
          'headers' => [
            'Authorization' => $token,
            'Content-Type' => $this->container->get('content.type')
          ],
          'body' => json_encode($request->getParsedBody()),
        ]);
      } catch (\GuzzleHttp\Exception\RequestException $e) {
        $response = new ResponseSlim();
        $jsonDecode = json_decode($e->getResponse()->getBody()->getContents(), true);
        // var_dump($jsonDecode);
        // die();
        $exceptionData = [
          'code' => $jsonDecode['exception'][0]['code'] ?? 500,
          'message' => $jsonDecode['exception'][0]['message'] ?? 'Erreur inconnue',
        ];

        $jsonExceptionData = json_encode($exceptionData, JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($jsonExceptionData);
        $response = $response->withHeader('Content-Type', 'application/json');
        return $response->withStatus($exceptionData['code']);
      }
    } else if ($route === '/signin') {
      try {
        $responseHTTP = $clientAuth->post($route, [
          'query' => $request->getQueryParams(),
          'headers' => [
            'Authorization' => $token,
            'Content-Type' => $this->container->get('content.type')
          ],
          'body' => json_encode($request->getParsedBody()),
        ]);
      } catch (\GuzzleHttp\Exception\RequestException $e) {
        $response = new ResponseSlim();
        $jsonDecode = json_decode($e->getResponse()->getBody()->getContents(), true);
        // var_dump($jsonDecode);
        // die();
        $exceptionData = [
          'code' => $jsonDecode['exception'][0]['code'] ?? 500,
          'message' => $jsonDecode['exception'][0]['message'] ?? 'Erreur inconnue',
        ];

        $jsonExceptionData = json_encode($exceptionData, JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($jsonExceptionData);
        $response = $response->withHeader('Content-Type', 'application/json');
        return $response->withStatus($exceptionData['code']);
      }
    } else if ($route === '/users' && $method === 'put') {
      try {
        $responseHTTP = $clientAuth->put($route, [
          'query' => $request->getQueryParams(),
          'headers' => [
            'Authorization' => $token,
            'Content-Type' => $this->container->get('content.type')
          ],
          'body' => json_encode($request->getParsedBody()),
        ]);
        $responseHTTP = $clientTedyspo->put($route, [
          'query' => $request->getQueryParams(),
          'headers' => [
            'Authorization' => $token,
            'Content-Type' => $this->container->get('content.type')
          ],
          'body' => json_encode($request->getParsedBody()),
        ]);
      } catch (\GuzzleHttp\Exception\RequestException $e) {
        $response = new ResponseSlim();
        $jsonDecode = json_decode($e->getResponse()->getBody()->getContents(), true);
        // var_dump($jsonDecode);
        // die();
        $exceptionData = [
          'code' => $jsonDecode['exception'][0]['code'] ?? 500,
          'message' => $jsonDecode['exception'][0]['message'] ?? 'Erreur inconnue',
        ];

        $jsonExceptionData = json_encode($exceptionData, JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($jsonExceptionData);
        $response = $response->withHeader('Content-Type', 'application/json');
        return $response->withStatus($exceptionData['code']);
      }
    } else {
      try {
        if ($method === "post") {
          $responseHTTP = $clientTedyspo->post($route, [
            'query' => $request->getQueryParams(),
            'headers' => [
              'Authorization' => $token,
              'Content-Type' => $this->container->get('content.type')
            ],
            'body' => json_encode($request->getParsedBody()),
          ]);
        } else if ($method === "get") {
          $responseHTTP = $clientTedyspo->get($route, [
            'query' => $request->getQueryParams(),
            'headers' => [
              'Authorization' => $token,
              'Content-Type' => $this->container->get('content.type')
            ],
          ]);
        } else if ($method === "put") {
          $responseHTTP = $clientTedyspo->put($route, [
            'query' => $request->getQueryParams(),
            'headers' => [
              'Authorization' => $token,
              'Content-Type' => $this->container->get('content.type')
            ],
            'body' => json_encode($request->getParsedBody()),
          ]);
        } else if ($method === "delete") {
          $responseHTTP = $clientTedyspo->delete($route, [
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
        $exceptionData = [
          'code' => $jsonDecode['exception'][0]['code'] ?? 500,
          'message' => $jsonDecode['exception'][0]['message'] ?? 'Erreur inconnue',
        ];

        $jsonExceptionData = json_encode($exceptionData, JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($jsonExceptionData);
        $response = $response->withHeader('Content-Type', 'application/json');
        return $response->withStatus($exceptionData['code']);
      }
    }

    $logger = $this->container->get('logger');
    $logger->info("{$method} | {$this->container->get('gateway.atelier.local')}{$route} | {$responseHTTP->getStatusCode()}");

    return $responseHTTP;
  }
}