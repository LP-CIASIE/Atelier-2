<?php

namespace atelier\gateway\actions\links;

use atelier\gateway\actions\AbstractAction;

class CreateLinkAction extends AbstractAction
{
  public function __invoke($request, $response, $args)
  {
    $linkService = $this->container->get('service.link');

    return $response;
  }
}
