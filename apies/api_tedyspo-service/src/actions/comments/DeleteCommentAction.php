<?php

namespace atelier\tedyspo\actions\comments;

use atelier\tedyspo\actions\AbstractAction;
use atelier\tedyspo\services\utils\FormatterAPI;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DeleteCommentAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $commentService = $this->container->get('service.comment');

    $commentService->delete($args['id_comment']);

    return FormatterAPI::formatResponse($request, $response, [], 204);
  }
}
