<?php

namespace atelier\tedyspo\actions\locations;

use atelier\tedyspo\actions\AbstractAction;
use atelier\tedyspo\services\utils\FormatterAPI;
use atelier\tedyspo\services\utils\FormatterObject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateLocationAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $locationService = $this->container->get('service.location');

    $data = $request->getParsedBody();
    $id_event = $args['id_event'];
    $location = $locationService->createLocation($data,$id_event);
    
    $data = [
      'location' => FormatterObject::Location($location)
    ];
    return FormatterAPI::formatResponse($request, $response, $data , 201);

  }
}
