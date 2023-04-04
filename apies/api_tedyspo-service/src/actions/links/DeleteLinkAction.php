<?php

namespace atelier\tedyspo\actions\links;

use atelier\tedyspo\actions\AbstractAction;
use atelier\tedyspo\services\utils\FormatterAPI;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DeleteLinkAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $linkService = $this->container->get('service.link');
    $linkService->deleteLink($args['id_link']);

    return FormatterAPI::formatResponse($request, $response, [], 204);
  }
}
