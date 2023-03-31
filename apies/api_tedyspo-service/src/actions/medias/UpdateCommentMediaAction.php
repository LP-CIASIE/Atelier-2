<?php

namespace atelier\tedyspo\actions\medias;

use atelier\tedyspo\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UpdateCommentMediaAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $mediaService = $this->container->get('service.media');

    return $response;
  }
}
