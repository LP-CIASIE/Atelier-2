<?php

namespace atelier\tedyspo\actions\links;

use atelier\tedyspo\actions\AbstractAction;

class UpdateLinkAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $linkService = $this->container->get('service.link');

    return $response;
  }
}
