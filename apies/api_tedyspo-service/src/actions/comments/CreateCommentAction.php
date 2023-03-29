<?php

namespace atelier\tedyspo\actions\comments;

use atelier\tedyspo\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateCommentAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $commentService = $this->container->get('service.comment');

    return $response;
  }
}
