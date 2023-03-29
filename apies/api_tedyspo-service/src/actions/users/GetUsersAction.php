<?php

namespace atelier\tedyspo\actions\users;

use atelier\tedyspo\actions\AbstractAction;
use atelier\tedyspo\services\utils\FormatterAPI;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetUsersAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    $params = $request->getQueryParams();

    $page = $params['page'] ?? 1;
    $size = $params['size'] ?? 10;

    $parameters = $request->getQueryParams();

    $userService = $this->container->get('service.user');

    $users = $userService->getUsers($parameters);
    $count = $userService->getCount();


    $data = FormatterAPI::formatPagination($request, 'get_users', $page, $parameters, $count, $size);
    $data['users'] = $users;

    return FormatterAPI::formatResponse($request, $response, $data, 200);
  }
}
