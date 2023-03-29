<?php

namespace atelier\tedyspo\services;

use Respect\Validation\Validator as v;

class JWTService extends AbstractService
{
  public function decodeDataOfJWT($Authorization)
  {
    $jwt = $Authorization[0] ?? null;

    try {
      v::stringType()->notEmpty()->validate($jwt);
    } catch (\Exception $e) {
      throw new \Exception('Token manquant', 400);
    }

    $jwt = explode('.', $jwt);

    if (count($jwt) !== 3) {
      throw new \Exception('Token invalide', 400);
    }

    $jwt = $jwt[1];
    $jwt = base64_decode($jwt);

    if ($jwt === false) {
      throw new \Exception('Token invalide', 400);
    }

    $jwt = json_decode($jwt, true);
    return $jwt;
  }
}
