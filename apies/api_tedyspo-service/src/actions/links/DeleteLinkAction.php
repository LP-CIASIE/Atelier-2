<?php

namespace atelier\tedyspo\actions\links;

use atelier\tedyspo\actions\AbstractAction;

class DeleteLinkAction extends AbstractAction
{
  public function __invoke($request, $response, $args)
  {
    $linkService = $this->container->get('service.link');

    return $response;
  }
}
