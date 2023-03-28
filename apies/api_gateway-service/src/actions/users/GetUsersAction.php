<?php

namespace atelier\gateway\actions\users;

use atelier\gateway\actions\AbstractAction;

class GetUsersAction extends AbstractAction
{
  public function __invoke($request, $response, $args)
  {
    $userService = $this->container->get('service.user');

    return $response;
  }
}
