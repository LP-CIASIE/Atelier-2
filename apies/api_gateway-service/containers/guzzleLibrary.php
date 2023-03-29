<?php
return [
  'client.tedyspo.service' => function (\Psr\Container\ContainerInterface $c): \GuzzleHttp\Client {
    return new \GuzzleHttp\Client([
      'base_uri' => $c->get('tedyspo.atelier.local'),
      'timeout' => $c->get('timeout.request.HTTP'),
    ]);
  },

  'client.auth.service' => function (\Psr\Container\ContainerInterface $c): \GuzzleHttp\Client {
    return new \GuzzleHttp\Client([
      'base_uri' => $c->get('auth.atelier.local'),
      'timeout' => $c->get('timeout.request.HTTP'),
    ]);
  },
];
