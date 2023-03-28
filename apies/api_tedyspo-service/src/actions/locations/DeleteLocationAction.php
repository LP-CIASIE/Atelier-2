<?php

namespace atelier\tedyspo\actions\locations;

use atelier\tedyspo\actions\AbstractAction;

class DeleteLocationAction extends AbstractAction
{
  public function __invoke($request, $response, $args)
  {
    $locationService = $this->container->get('service.location');

    return $response;
  }
}
