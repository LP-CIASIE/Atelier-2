<?php

namespace atelier\gateway\actions\auth;

use atelier\gateway\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class SignInAction extends AbstractAction
{
  public function __invoke(
    Request $request,
    Response $response
  ) {
    return $this->sendRequest($request, $response, '/signin', 'post');
  }
}
