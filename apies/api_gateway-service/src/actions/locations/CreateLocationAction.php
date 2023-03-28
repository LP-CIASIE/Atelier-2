<?php

namespace atelier\gateway\actions\locations;

use atelier\gateway\actions\AbstractAction;

class CreateLocationAction extends AbstractAction
{
  public function __invoke($request, $response, $args)
  {
    $locationService = $this->container->get('service.location');

    return $response;
  }
}
