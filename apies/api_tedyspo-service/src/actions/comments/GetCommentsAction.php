<?php

namespace atelier\tedyspo\actions\comments;

use atelier\tedyspo\actions\AbstractAction;
use atelier\tedyspo\services\utils\FormatterAPI;
use atelier\tedyspo\services\utils\FormatterObject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetCommentsAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $params = $request->getQueryParams();

    $page = $params['page'] ?? 1;
    $size = $params['size'] ?? 10;

    $commentService = $this->container->get('service.comment');

    $comments = $commentService->getComments($args['id_event']);
    $count = $commentService->getCount($args['id_event']);

    $data = FormatterAPI::formatPagination($request, 'get_comments', $page, $params, $count, $size);
    $data['comments'] = FormatterObject::Comments($comments);

    return FormatterAPI::formatResponse($request, $response, $data, 200);
  }
}
