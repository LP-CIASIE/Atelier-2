<?php

namespace atelier\tedyspo\actions\locations;

use atelier\tedyspo\actions\AbstractAction;
use atelier\tedyspo\services\utils\FormatterAPI;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateLocationAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $locationService = $this->container->get('service.location');

    $data = $request->getParsedBody();
    $params = $request->getQueryParams();
    $id_event = $params['id_event'];
    $locationService->createLocation($data,$id_event);
    FormatterAPI::formatResponse($request, $response, $data, 201); 

  }
}
