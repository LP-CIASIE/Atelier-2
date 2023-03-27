<?php

return [
  'logger' => function (\Psr\Container\ContainerInterface $c) {
    $logger = new \Monolog\Logger('lbs-gateway-frontoffice');
    $file_handler = new \Monolog\Handler\StreamHandler(dirname(__DIR__, 1) . '/log/app.log');
    $logger->pushHandler($file_handler);
    return $logger;
  },
];
