<?php

namespace atelier\gateway\actions\auth;

use atelier\gateway\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class SignUpAction extends AbstractAction
{
  public function __invoke(
    Request $request,
    Response $response
  ) {
    $responseHTTP = $this->sendRequest($request, $response, '/signup', 'post', 'auth');
    $data = json_decode($responseHTTP->getBody(), true);
    $id_user = $data['id_user'];
    return $this->sendRequest($request, $response, '/signup/' . $id_user['id_user'], 'post', 'tedyspo');
  }
}
