<?php

namespace atelier\tedyspo\actions\locations;

use atelier\tedyspo\actions\AbstractAction;

class UpdateLocationAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $locationService = $this->container->get('service.location');

    return $response;
  }
}
