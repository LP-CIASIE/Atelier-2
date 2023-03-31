<?php

namespace atelier\tedyspo\actions\comments;

use atelier\tedyspo\actions\AbstractAction;
use atelier\tedyspo\services\utils\FormatterAPI;
use atelier\tedyspo\services\utils\FormatterObject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetCommentAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $commentService = $this->container->get('service.comment');

    $comment = $commentService->get($args['id_comment']);

    $data = [
      'comment' => FormatterObject::Comment($comment)
    ];

    return FormatterAPI::formatResponse($request, $response, $data, 200);
  }
}
