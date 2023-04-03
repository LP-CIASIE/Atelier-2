<?php

namespace atelier\tedyspo\actions\links;

use atelier\tedyspo\actions\AbstractAction;
use atelier\tedyspo\services\utils\FormatterAPI;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UpdateLinkAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $data = $this->parseBody($request);

    $linkService = $this->container->get('service.link');
    $linkService->updateLink($data, $args['id_link']);

    return FormatterAPI::formatResponse($request, $response, [], 204);
  }
}
