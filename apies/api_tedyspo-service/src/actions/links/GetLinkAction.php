<?php

namespace atelier\tedyspo\actions\links;

use atelier\tedyspo\actions\AbstractAction;
use atelier\tedyspo\services\utils\FormatterAPI;
use atelier\tedyspo\services\utils\FormatterObject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetLinkAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $linkService = $this->container->get('service.link');

    $link = $linkService->getLink($args['id_link']);

    $data = [
      'url' => FormatterObject::Link($link)
    ];

    return FormatterAPI::formatResponse($request, $response, $data, 200);
  }
}
