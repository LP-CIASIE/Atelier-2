<?php

namespace atelier\tedyspo\actions\locations;

use atelier\tedyspo\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use atelier\tedyspo\services\utils\FormatterObject;
use atelier\tedyspo\services\utils\FormatterAPI;

class UpdateLocationAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $locationService = $this->container->get('service.location');
    $id_location = $args['id_location'];
    $id_event = $args['id_event'];
    $data = $request->getParsedBody();
    $location  = $locationService->updateLocation($data, $id_event, $id_location);

    $data = [
      'location' => FormatterObject::Location($location)
    ];
    return FormatterAPI::formatResponse($request, $response, $data , 201);


    return $response;
  }
}
