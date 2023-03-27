<?php

namespace atelier\tedyspo\actions\events;

use atelier\tedyspo\actions\AbstractAction;

class GetEventUserAction extends AbstractAction
{
  public function __invoke($request, $response, $args)
  {
    return $response;
  }
}
