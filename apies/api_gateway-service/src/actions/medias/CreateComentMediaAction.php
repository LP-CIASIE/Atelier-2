<?php

namespace atelier\gateway\actions\medias;

use atelier\gateway\actions\AbstractAction;

class CreateComentMediaAction extends AbstractAction
{
  public function __invoke($request, $response, $args)
  {
    $mediaService = $this->container->get('service.media');

    return $response;
  }
}
