<?php
return [
  'client.tedyspo.service' => function (\Psr\Container\ContainerInterface $c) {
    return new \GuzzleHttp\Client([
      'base_uri' => $c->get('tedyspo.service.uri'),
      'timeout' => $c->get('timeout.request.HTTP'),
    ]);
  },

  'client.auth.service' => function (\Psr\Container\ContainerInterface $c) {
    return new \GuzzleHttp\Client([
      'base_uri' => $c->get('auth.service.uri'),
      'timeout' => $c->get('timeout.request.HTTP'),
    ]);
  },
];
