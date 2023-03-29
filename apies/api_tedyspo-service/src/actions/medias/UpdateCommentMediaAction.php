<?php

namespace atelier\tedyspo\actions\medias;

use atelier\tedyspo\actions\AbstractAction;

class UpdateCommentMediaAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $mediaService = $this->container->get('service.media');

    return $response;
  }
}
