<?php

namespace atelier\auth\services;

use atelier\auth\models\User;
use Respect\Validation\Exceptions\ValidatorException;
use Respect\Validation\Validator as Validator;

class UserService
{

  public function createUser($body): void
  {
    try {
      Validator::key('email', Validator::email()->length(5, 50)->notEmpty())
        ->key('password', Validator::stringType()->length(8, 255)->notEmpty())
        ->key('firstname', Validator::stringType()->length(1, 30)->notEmpty())
        ->key('role', Validator::stringType())
        ->assert($body);
    } catch (ValidatorException $e) {
      throw new \Exception("Donnée du body invalides.", 400);
    }

    if ($body['role'] !== 'admin' && $body['role'] !== 'user') {
      throw new \Exception("Le role doit être 'admin' ou 'user'.");
    }
    $userExist = User::where('email', $body['email'])->first();

    if ($userExist) {
      throw new \Exception("L'utilisateur existe déjà.", 409);
    }

    $user = new User();
    try {
      $user->id_user = \Ramsey\Uuid\Uuid::uuid4()->toString();
      $user->email = $body['email'];
      $user->password = password_hash($body['password'], PASSWORD_BCRYPT, ['cost' => 12]);
      $user->refresh_token = base64_encode(random_bytes(150));
      $user->role = $body['role'];

      $user->save();
    } catch (\Exception $e) {
      throw new \Exception("Erreur lors de la création de l'utilisateur.", 500);
    }
  }

  public function login($body): User
  {
    try {
      Validator::key('email', Validator::email()->length(5, 50)->notEmpty())
        ->key('password', Validator::stringType()->length(8, 255)->notEmpty())
        ->assert($body);
    } catch (ValidatorException $e) {
      throw new \Exception("Donnée du body invalides.", 400);
    }

    $user = User::where('email', $body['email'])->first();

    if (!$user || !password_verify($body['password'], $user->password)) {
      throw new \Exception("Mail ou mot de passe invalide.", 401);
    }

    return $user;
  }
}
