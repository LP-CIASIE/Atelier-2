<?php

namespace atelier\tedyspo\actions\comments;

use atelier\tedyspo\actions\AbstractAction;

class GetCommentsAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $commentService = $this->container->get('service.comment');

    return $response;
  }
}
