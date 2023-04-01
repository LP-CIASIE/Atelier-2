<?php

namespace atelier\tedyspo\actions\locations;

use atelier\tedyspo\actions\AbstractAction;
use atelier\tedyspo\services\utils\FormatterObject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use atelier\tedyspo\services\utils\FormatterAPI;

class DeleteLocationAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $locationService = $this->container->get('service.location');
    $id_location = $args['id_location'];
    $id_event = $args['id_event'];
    $location  = $locationService->deleteLocation($id_event, $id_location);

    
    return FormatterAPI::formatResponse($request, $response, [] , 204);
  }
}
