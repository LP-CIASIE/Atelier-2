<?php

namespace atelier\gateway\actions\locations;

use atelier\gateway\actions\AbstractAction;

class GetLocationsAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $locationService = $this->container->get('service.location');
  }
}
