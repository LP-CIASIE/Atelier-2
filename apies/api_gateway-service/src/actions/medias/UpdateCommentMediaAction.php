<?php

namespace atelier\gateway\actions\medias;

use atelier\gateway\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UpdateCommentMediaAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    return $this->sendRequest($request, $response, '/comments/' . $args['id_comment'] . '/medias/' . $args['id_media'], 'put');
  }
}
