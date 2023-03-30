<?php

namespace atelier\tedyspo\services\utils;

use atelier\tedyspo\models\User;
use atelier\tedyspo\models\Event;
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

  public static function Event(Event $event)
  {
    return [
      'id_event' => $event->id_event,
      'title' => $event->title,
      'description' => $event->description,
      'date' => $event->date,
      'links' => [
        'self' => [
          'href' => '/events/' . $event->id_event
        ],
      ]
    ];
  }

  public static function Events($events)
  {
    $eventsArray = [];
    foreach ($events as $event) {
      $eventsArray[] = self::Event($event);
    }
    return $eventsArray;
  }

}
