<?php

namespace atelier\tedyspo\services;

class JWTService extends AbstractService
{
  public function decodeDataOfJWT($jwt)
  {
    $jwt = explode('.', $jwt);
    $jwt = $jwt[1];
    $jwt = base64_decode($jwt);
    $jwt = json_decode($jwt, true);
    return $jwt;
  }
}
