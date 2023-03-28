<?php

namespace atelier\gateway\actions\users;

use atelier\gateway\actions\AbstractAction;

class GetUserAction extends AbstractAction
{
  public function __invoke($request, $response, $args)
  {
    $this->sendRequest($request, $response, '/users/' . $args['id_user'], 'get');
  }
}
