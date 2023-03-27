<?php

namespace atelier\auth\services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

final class TokenService
{

  static public function createToken($username)
  {
    $c = new \MongoDB\Client("mongodb://mongo.auth:27017");
    $db = $c->auth_service;
    $user = $db->users->findOne(['usermail' => $username]);

    $data = require __DIR__ . '/../../data/settings.php';
    $secret = $data['JWT_SECRET'];

    $payload = [
      'iss' => 'http://auth.myapp.net',
      'aud' => 'http://api.myapp.net',
      'iat' => time(), 'exp' => time() + 3600,
      'uid' => $user->_id,
      'mail' => $user->usermail,
      'lvl' => $user->userlevel,
    ];

    $accessToken = JWT::encode($payload, $secret, 'HS512');
    $refreshToken = $user->refresh_token;


    return (['access' => $accessToken, 'refresh' => $refreshToken]);
  }

  function verifyToken($fullToken)
  {
    if (empty($fullToken)) {
      throw new \Exception("Token manquant.");
    }

    $tokenJWT = sscanf($fullToken, "Bearer %s")[0];

    if (empty($tokenJWT)) {
      throw new \Exception("Format du token invalide.");
    }

    $params = require __DIR__ . '/../../data/settings.php';
    $secret = $params['JWT_SECRET'];

    try {
      JWT::decode($tokenJWT, new Key($secret, 'HS512'));
    } catch (\Exception $e) {
      throw new \Exception("Token invalide.");
    }
  }
}
