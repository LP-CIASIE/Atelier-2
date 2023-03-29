<?php

namespace atelier\tedyspo\services\utils;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;

class FormatterAPI
{
  public static function formatResponse(Request $request, Response $response, $data, $status = 200, $message = null): Response
  {
    $response = $response->withStatus($status);
    $response = $response->withHeader('Content-Type', 'application/json');

    $response->getBody()->write(json_encode($data));

    return $response;
  }

  public static function formatPagination(Request $rq, string $nameRoute, int $actualPagination, array $parameters, int $count, int $size): array
  {
    $routeParser = RouteContext::fromRequest($rq)->getRouteParser();

    $data = [
      'count' => $count,
      'links' => [
        'self' => $routeParser->urlFor($nameRoute, [], array_merge($parameters, ['page' => $actualPagination, 'size' => $size])),
        'first' => $routeParser->urlFor($nameRoute, [], array_merge($parameters, ['page' => 1, 'size' => $size])),
        'last' => $routeParser->urlFor($nameRoute, [], array_merge($parameters, ['page' => (ceil($count / $size) > 0 ? ceil($count / $size) : 1), 'size' => $size])),
      ],
    ];

    if ($actualPagination > 1) {
      $data['links']['prev'] = $routeParser->urlFor($nameRoute, [], array_merge($parameters, ['page' => $actualPagination - 1, 'size' => $size]));
    }

    if ($actualPagination < ceil($count / $size)) {
      $data['links']['next'] = $routeParser->urlFor($nameRoute, [], array_merge($parameters, ['page' => $actualPagination + 1, 'size' => $size]));
    }


    return $data;
  }

  public static function formatPagination(Request $rq, string $nameRoute, int $actualPagination, array $parameters, int $count, int $size): array
  {
    $routeParser = RouteContext::fromRequest($rq)->getRouteParser();

    $data = [
      'count' => $count,
      'links' => [
        'self' => $routeParser->urlFor($nameRoute, [], array_merge($parameters, ['page' => $actualPagination, 'size' => $size])),
        'first' => $routeParser->urlFor($nameRoute, [], array_merge($parameters, ['page' => 1, 'size' => $size])),
        'last' => $routeParser->urlFor($nameRoute, [], array_merge($parameters, ['page' => (ceil($count / $size) > 0 ? ceil($count / $size) : 1), 'size' => $size])),
      ],
    ];

    if ($actualPagination > 1) {
      $data['links']['prev'] = $routeParser->urlFor($nameRoute, [], array_merge($parameters, ['page' => $actualPagination - 1, 'size' => $size]));
    }

    if ($actualPagination < ceil($count / $size)) {
      $data['links']['next'] = $routeParser->urlFor($nameRoute, [], array_merge($parameters, ['page' => $actualPagination + 1, 'size' => $size]));
    }


    return $data;
  }

  public static function formatPagination(Request $rq, string $nameRoute, int $actualPagination, array $parameters, int $count, int $size): array
  {
    $routeParser = RouteContext::fromRequest($rq)->getRouteParser();

    $data = [
      'count' => $count,
      'links' => [
        'self' => $routeParser->urlFor($nameRoute, [], array_merge($parameters, ['page' => $actualPagination, 'size' => $size])),
        'first' => $routeParser->urlFor($nameRoute, [], array_merge($parameters, ['page' => 1, 'size' => $size])),
        'last' => $routeParser->urlFor($nameRoute, [], array_merge($parameters, ['page' => (ceil($count / $size) > 0 ? ceil($count / $size) : 1), 'size' => $size])),
      ],
    ];

    if ($actualPagination > 1) {
      $data['links']['prev'] = $routeParser->urlFor($nameRoute, [], array_merge($parameters, ['page' => $actualPagination - 1, 'size' => $size]));
    }

    if ($actualPagination < ceil($count / $size)) {
      $data['links']['next'] = $routeParser->urlFor($nameRoute, [], array_merge($parameters, ['page' => $actualPagination + 1, 'size' => $size]));
    }


    return $data;
  }
}
