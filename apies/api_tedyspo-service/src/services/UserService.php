<?php

namespace atelier\tedyspo\services;

use atelier\tedyspo\models\User;

use Respect\Validation\Validator as v;

class UserService extends AbstractService
{

  public function getCount()
  {
    return User::count();
  }

  public function getUsers($parameters)
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

    $users = User::where('email', 'like', '%@%')
      ->orderBy('email', 'desc')
      ->skip(($page - 1) * $size)
      ->take($size)
      ->get();

    return $users;
  }
}
