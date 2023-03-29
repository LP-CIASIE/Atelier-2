<?php

namespace atelier\tedyspo\services;

use atelier\tedyspo\models\User;

use Respect\Validation\Validator as v;

class UserService extends AbstractService
{

  public function getCount($params = [])
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

    $users = User::where('email', 'like', '%' . $email . '%')
      ->orderBy('email', 'desc')
      ->skip(($page - 1) * $size)
      ->take($size)
      ->get();

    return $users;
  }
}
