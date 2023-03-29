<?php

namespace atelier\gateway\actions\users;

use atelier\gateway\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UpdateUserAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    return $this->sendRequest($request, $response, '/users/' . $args['id_user'], 'put');
  }
}
