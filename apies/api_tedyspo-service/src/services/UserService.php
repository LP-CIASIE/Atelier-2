<?php

namespace atelier\tedyspo\services;

use atelier\tedyspo\models\User;
use Illuminate\Database\Eloquent\Collection;
use Respect\Validation\Validator as v;

class UserService extends AbstractService
{

  public function getCount($params = []): int
  {
    if (count($params) == 0) {
      return User::count();
    }

    $counter = User::where($params[0][0], $params[0][1], $params[0][2]);
    for ($i = 1; $i < count($params); $i++) {
      $counter = $counter->where($params[$i][0], $params[$i][1], $params[$i][2]);
    }
    return $counter->count();
  }

  public function getUsers($parameters): Collection
  {
    $page = $parameters['page'] ?? 1;
    $size = $parameters['size'] ?? 10;
    $email = $parameters['email'] ?? '';


    try {
      v::intVal()->min(1)->assert($size);
    } catch (\Exception $e) {
      throw new \InvalidArgumentException('Format de donnée pour la taille est incorrect.', 400);
    }

    try {
      v::intVal()->min(1)->assert($page);
    } catch (\Exception $e) {
      throw new \InvalidArgumentException('Format de donnée pour la page est incorrect.', 400);
    }

    try {
      v::length(3, 100)->assert($email);
    } catch (\Exception $e) {
      throw new \InvalidArgumentException('Recherche par email trop petite, 3 lettres minimum.', 400);
    }

    $users = User::where('email', 'like', '%' . $email . '%')
      ->orderBy('email', 'desc')
      ->skip(($page - 1) * $size)
      ->take($size)
      ->get();

    return $users;
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
        v::email()->assert($data['email']);
      } catch (\Exception $e) {
        throw new \InvalidArgumentException('Format de donnée pour l\'email est incorrect.', 400);
      }
      $user->email = $data['email'];
      $changed = true;
    }

    if (isset($data['firstname'])) {
      try {
        v::length(1, 30)->assert($data['firstname']);
      } catch (\Exception $e) {
        throw new \InvalidArgumentException('Format de donnée pour le prénom est incorrect.', 400);
      }
      $user->firstname = $data['firstname'];
      $changed = true;
    }

    if (isset($data['lastname'])) {
      try {
        v::length(1, 30)->assert($data['lastname']);
      } catch (\Exception $e) {
        throw new \InvalidArgumentException('Format de donnée pour le nom est incorrect.', 400);
      }
      $user->lastname = $data['lastname'];
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

  public function createUser($data): User
  {
    $user = new User();
    $user->id_user = $data['id_user'];

    try {
      v::email()->assert($data['email']);
    } catch (\Exception $e) {
      throw new \InvalidArgumentException('Format de donnée pour l\'email est incorrect.', 400);
    }

    if (User::where('email', $data['email'])->count() > 0) {
      throw new \Exception('Email déjà utilisé', 409);
    }

    $user->email = $data['email'];

    try {
      v::length(1, 30)->assert($data['firstname']);
      $user->firstname = $data['firstname'];
    } catch (\Exception $e) {
      throw new \InvalidArgumentException('Format de donnée pour le prénom est incorrect.', 400);
    }

    if (isset($data['lastname'])) {
      try {
        v::length(1, 30)->assert($data['lastname']);
        $user->lastname = $data['lastname'];
      } catch (\Exception $e) {
        throw new \InvalidArgumentException('Format de donnée pour le nom est incorrect.', 400);
      }
    }


    try {
      $user->save();
    } catch (\Exception $e) {
      throw new \Exception('Erreur lors de la création de l\'utilisateur', 500);
    }

    return $user;
  }
}
