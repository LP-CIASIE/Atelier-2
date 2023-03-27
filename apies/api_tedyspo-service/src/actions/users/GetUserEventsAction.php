<?php

namespace atelier\tedyspo\actions\users;

use atelier\tedyspo\actions\AbstractAction;

class GetUserEventsAction extends AbstractAction
{
  public function __invoke($request, $response, $args)
  {
    $userService = $this->container->get('service.users');
    $users = $userService->getUsers();

    $data = [
      'users' => $users
    ];

    $response = $response->withJson($data, 200);

    return $response;
  }
}
