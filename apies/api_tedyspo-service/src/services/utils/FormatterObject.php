<?php

namespace atelier\tedyspo\services\utils;

use atelier\tedyspo\models\User;

class FormatterObject
{
  public static function User(User $user)
  {
    return [
      'id' => $user->id_user,
      'email' => $user->email,
      'firstname' => $user->firstname,
      'lastname' => $user->lastname,
      'links' => [
        'self' => [
          'href' => '/users/' . $user->id_user
        ],
        'events' => [
          'href' => '/users/' . $user->id_user . '/events'
        ],
      ]
    ];
  }

  public static function Users($users)
  {
    $usersArray = [];
    foreach ($users as $user) {
      $usersArray[] = self::User($user);
    }
    return $usersArray;
  }
}
