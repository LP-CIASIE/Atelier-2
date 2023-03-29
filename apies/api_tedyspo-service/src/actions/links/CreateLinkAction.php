<?php

namespace atelier\tedyspo\actions\links;

use atelier\tedyspo\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateLinkAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $linkService = $this->container->get('service.link');

    return $response;
  }
}
