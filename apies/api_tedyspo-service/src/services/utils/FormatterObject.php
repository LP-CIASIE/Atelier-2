<?php

namespace atelier\tedyspo\services\utils;

use atelier\tedyspo\models\User;

class FormatterObject
{
  public static function User(User $user)
  {
    return [
      'id' => $user->id,
      'email' => $user->email,
      'firstname' => $user->firstname,
      'lastname' => $user->lastname,
      'links' => [
        'self' => [
          'href' => '/users/' . $user->id
        ],
        'events' => [
          'href' => '/users/' . $user->id . '/events'
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
