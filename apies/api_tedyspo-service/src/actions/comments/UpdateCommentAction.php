<?php

namespace atelier\tedyspo\actions\comments;

use atelier\tedyspo\actions\AbstractAction;
use atelier\tedyspo\services\utils\FormatterAPI;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UpdateCommentAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $body = $this->parseBody($request);
    $user = $this->parseJWT($request);

    $commentService = $this->container->get('service.comment');
    $commentService->updateComment($args['id_comment'], $user['uid'], $body);

    return FormatterAPI::formatResponse($request, $response, [], 204);
  }
}
