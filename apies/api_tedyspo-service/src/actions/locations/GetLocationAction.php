<?php

namespace atelier\tedyspo\actions\locations;

use atelier\tedyspo\actions\AbstractAction;

class GetLocationAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $locationService = $this->container->get('service.location');

    return $response;
  }
}
