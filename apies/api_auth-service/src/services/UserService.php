<?php

namespace atelier\auth\services;

use atelier\auth\models\User;
use Respect\Validation\Exceptions\ValidatorException;
use Respect\Validation\Validator as v;

class UserService
{

  public function createUser($body): array
  {
    try {
      v::key('email', v::email()->length(5, 50)->notEmpty())
        ->key('password', v::stringType()->length(8, 255)->notEmpty())
        ->key('role', v::stringType())
        ->assert($body);
    } catch (ValidatorException $e) {
      throw new \Exception("Donnée du body invalides.", 400);
    }

    if ($body['role'] !== 'admin' && $body['role'] !== 'user') {
      throw new \Exception("Le role doit être 'admin' ou 'user'.");
    }
    $userExist = User::where('email', $body['email'])->first();

    if ($userExist) {
      throw new \Exception("Un compte est déjà associé à cette adresse email.", 409);
    }

    $user = new User();
    try {
      $user->id_user = \Ramsey\Uuid\Uuid::uuid4()->toString();
      $user->email = $body['email'];
      $user->password = password_hash($body['password'], PASSWORD_BCRYPT, ['cost' => 12]);
      $user->refresh_token = base64_encode(random_bytes(150));
      $user->role = $body['role'];

      $user->save();

      $data = [
        'id_user' => $user->id_user,
      ];
    } catch (\Exception $e) {
      throw new \Exception("Erreur lors de la création de l'utilisateur.", 500);
    }

    return $data;
  }

  public function login($body): User
  {
    try {
      v::key('email', v::email()->length(5, 50)->notEmpty())
        ->key('password', v::stringType()->length(8, 255)->notEmpty())
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

  public function getUserById($id): User
  {
    try {
      v::uuid()->assert($id);
    } catch (\Exception $e) {
      throw new \InvalidArgumentException('Format de donnée pour l\'id est incorrect.', 400);
    }

    try {
      $user = User::findOrFail($id);
    } catch (\Exception $e) {
      throw new \Exception('Utilisateur introuvable', 404);
    }

    return $user;
  }

  public function updateUserById($id, $data): void
  {
    $user = $this->getUserById($id);
    $changed = false;

    if (isset($data['email'])) {
      try {
        v::email()->length(4, 50)->assert($data['email']);
      } catch (\Exception $e) {
        throw new \InvalidArgumentException('Format de donnée pour l\'email est incorrect.', 400);
      }
      $user->email = $data['email'];
      $changed = true;
    }

    if (isset($data['password'])) {
      try {
        v::stringType()->length(8, 255)->assert($data['password']);
      } catch (\Exception $e) {
        throw new \InvalidArgumentException('Format de donnée pour le mot de passe est incorrect.', 400);
      }
      $user->password = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
      $changed = true;
    }

    try {
      if ($changed) {
        $user->save();
      } else {
        throw new \Exception('Aucune donnée à mettre à jour', 400);
      }
    } catch (\Exception $e) {
      throw new \Exception('Erreur lors de la mise à jour de l\'utilisateur', 500);
    }
  }

  public function deleteUserById($id): void
  {
    $user = $this->getUserById($id);

    try {
      $user->delete();
    } catch (\Exception $e) {
      throw new \Exception('Erreur lors de la suppression de l\'utilisateur', 500);
    }
  }
}
