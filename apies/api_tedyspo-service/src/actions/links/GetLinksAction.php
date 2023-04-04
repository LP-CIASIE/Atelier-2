<?php

namespace atelier\tedyspo\actions\links;

use atelier\tedyspo\services\utils\FormatterAPI;
use atelier\tedyspo\actions\AbstractAction;
use atelier\tedyspo\services\utils\FormatterObject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetLinksAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $linkService = $this->container->get('service.link');

    $links = $linkService->getLinks($args['id_event']);
    $count = $linkService->getCount($args['id_event']);

    $data = [
      'count' => $count,
      'urls' => FormatterObject::Links($links)
    ];

    return FormatterAPI::formatResponse($request, $response, $data, 200);
  }
}
