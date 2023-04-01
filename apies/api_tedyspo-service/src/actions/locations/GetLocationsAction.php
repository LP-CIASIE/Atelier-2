<?php

namespace atelier\tedyspo\actions\locations;

use atelier\tedyspo\actions\AbstractAction;
use atelier\tedyspo\services\utils\FormatterObject;
use atelier\tedyspo\services\utils\FormatterAPI;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetLocationsAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $locationService = $this->container->get('service.location');
    $id_event = $args['id_event'];
    $location  = $locationService->getLocations($id_event);
    $data = [
      'locations' => FormatterObject::Locations($location)
    ];
    return FormatterAPI::formatResponse($request, $response, $data , 200);
  }
}
