<?php

namespace atelier\tedyspo\actions\medias;

use atelier\tedyspo\actions\AbstractAction;

class CreateComentMediaAction extends AbstractAction
{
  public function __invoke($request, $response, $args)
  {
    $mediaService = $this->container->get('service.media');

    return $response;
  }
}
