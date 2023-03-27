<?php

return [
  'service.user' => DI\factory(function () {
    return new atelier\tedyspo\services\UserService();
  }),
];
