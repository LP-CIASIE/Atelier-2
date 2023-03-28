<?php

namespace atelier\tedyspo\actions\users;

use atelier\tedyspo\actions\AbstractAction;

class CreateUserAction extends AbstractAction
{
  public function __invoke($request, $response, $args)
  {
    $userService = $this->container->get('service.user');

    return $response;
  }
}
