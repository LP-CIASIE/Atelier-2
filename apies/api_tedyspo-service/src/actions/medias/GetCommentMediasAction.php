<?php

namespace atelier\tedyspo\actions\medias;

use atelier\tedyspo\actions\AbstractAction;

class GetCommentMediasAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $mediaService = $this->container->get('service.media');

    return $response;
  }
}
