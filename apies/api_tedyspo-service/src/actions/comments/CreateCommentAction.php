<?php

namespace atelier\tedyspo\actions\comments;

use atelier\tedyspo\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateCommentAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $user = $this->parseJWT($request);

    $data = $this->parseBody($request);

    $commentService = $this->container->get('service.comment');
    $comment = $commentService->create($data, $user['uid'], $args['id_event']);

    return $response;
  }
}
