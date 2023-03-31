<?php

namespace atelier\auth\services;

use atelier\auth\models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Respect\Validation\Validator as v;
final class TokenService
{
  static public function generateToken(User $user): array
  {
    $data = require __DIR__ . '/../../data/settings.php';
    $secret = $data['JWT_SECRET'];

    $payload = [
      'iss' => 'http://auth.myapp.net',
      'aud' => 'http://api.myapp.net',
      'iat' => time(), 'exp' => time() + 3600,
      'uid' => $user->id_user,
      'email' => $user->email,
      'role' => $user->role,
    ];

    $accessToken = JWT::encode($payload, $secret, 'HS512');
    $refreshToken = $user->refresh_token;


    return (['access' => $accessToken, 'refresh_token' => $refreshToken]);
  }

  function verifyToken($fullToken)
  {
    if (empty($fullToken)) {
      throw new \Exception("Token manquant.", 400);
    }

    $tokenJWT = sscanf($fullToken[0], "Bearer %s")[0];

    if (empty($tokenJWT)) {
      throw new \Exception("Format du token invalide.", 400);
    }

    $params = require __DIR__ . '/../../data/settings.php';
    $secret = $params['JWT_SECRET'];

    try {
      JWT::decode($tokenJWT, new Key($secret, 'HS512'));
    } catch (\Exception $e) {
      throw new \Exception("Token invalide.", 401);
    }
  }

  public function decodeDataOfJWT($Authorization): array
  {
    $jwt = $Authorization[0] ?? '';

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