<?php

namespace atelier\tedyspo\actions\events;

use atelier\tedyspo\services\utils\FormatterAPI;
use atelier\tedyspo\actions\AbstractAction;
use atelier\tedyspo\services\utils\FormatterObject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetEventsAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $user = $this->parseJWT($request);
    $params = $request->getQueryParams();

    $page = $params['page'] ?? 1;
    $size = $params['size'] ?? 10;

    $eventService = $this->container->get('service.event');
    $events = $eventService->getEvents($user['uid'], $params);
    $count = $eventService->getCount($user['uid']);

    $data = FormatterAPI::formatPagination($request, 'get_events', $page, $params, $count, $size);
    $data['events'] = FormatterObject::Events($events);
    return FormatterAPI::formatResponse($request, $response, $data);
  }
}
