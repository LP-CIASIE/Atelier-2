<?php

namespace atelier\gateway\actions\users;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use atelier\gateway\actions\AbstractAction;

class GetUserAction extends AbstractAction
{
  public function __invoke(Request $request, Response $response, $args)
  {
    //TODO Traiter les gets avec des paramètres (ex: /users/1?param1=truc&param2=bidule) (Je sais pas comment faire ça)
    return $this->sendRequest($request, $response, '/users/' . $args['id_user'], 'get');
  }
}
