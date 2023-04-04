<?php

namespace atelier\tedyspo\actions\links;

use atelier\tedyspo\actions\AbstractAction;
use atelier\tedyspo\services\utils\FormatterAPI;
use atelier\tedyspo\services\utils\FormatterObject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateLinkAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $data = $this->parseBody($request);

    $linkService = $this->container->get('service.link');
    $link = $linkService->createLink($data, $args['id_event']);

    $data = [
      'url' => FormatterObject::Link($link)
    ];

    return FormatterAPI::formatResponse($request, $response, $data, 201);
  }
}
