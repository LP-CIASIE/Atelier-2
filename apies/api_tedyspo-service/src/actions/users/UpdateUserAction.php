<?php

namespace atelier\tedyspo\actions\users;

use atelier\tedyspo\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UpdateUserAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $userService = $this->container->get('service.user');

    return $response;
  }
}
