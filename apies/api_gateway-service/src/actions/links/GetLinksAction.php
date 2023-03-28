<?php

namespace atelier\gateway\actions\links;

use atelier\gateway\actions\AbstractAction;

class GetLinksAction extends AbstractAction
{
  public function __invoke($request, $response, $args)
  {
    $linkService = $this->container->get('service.link');

    return $response;
  }
}
