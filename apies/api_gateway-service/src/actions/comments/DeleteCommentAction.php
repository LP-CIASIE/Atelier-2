<?php

namespace atelier\gateway\actions\comments;

use atelier\gateway\actions\AbstractAction;

class DeleteCommentAction extends AbstractAction
{
  public function __invoke($request, $response, $args)
  {
    $commentService = $this->container->get('service.comment');

    return $response;
  }
}
