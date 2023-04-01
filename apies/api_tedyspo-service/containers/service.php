<?php

return [
  'service.user' => function (\Psr\Container\ContainerInterface  $c) {
    return new atelier\tedyspo\services\UserService($c);
  },
  'service.comment' => function (\Psr\Container\ContainerInterface  $c) {
    return new atelier\tedyspo\services\CommentService($c);
  },
  'service.event' => function (\Psr\Container\ContainerInterface  $c) {
    return new atelier\tedyspo\services\EventService($c);
  },
  'service.link' => function (\Psr\Container\ContainerInterface  $c) {
    return new atelier\tedyspo\services\LinkService($c);
  },
  'service.location' => function (\Psr\Container\ContainerInterface  $c) {
    return new atelier\tedyspo\services\LocationService($c);
  },
  'service.media' => function (\Psr\Container\ContainerInterface  $c) {
    return new atelier\tedyspo\services\MediaService($c);
  },
  'service.jwt' => function (\Psr\Container\ContainerInterface  $c) {
    return new atelier\tedyspo\services\JWTService($c);
  },
  'service.invitation' => function (\Psr\Container\ContainerInterface  $c) {
    return new atelier\tedyspo\services\InvitationService($c);
  },
];
