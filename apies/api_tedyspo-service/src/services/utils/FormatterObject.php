<?php

namespace atelier\tedyspo\services\utils;

use atelier\tedyspo\models\Comment;
use atelier\tedyspo\models\Event;
use atelier\tedyspo\models\User;
use atelier\tedyspo\models\Location;
use Illuminate\Database\Eloquent\Collection;

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

  public static function Comment(Comment $comment)
  {
    return [
      'id' => $comment->id_comment,
      'comment' => $comment->comment,
      'links' => [
        'user' => [
          'href' => '/users/' . $comment->id_user
        ],
        'event' => [
          'href' => '/events/' . $comment->id_event
        ],
      ]
    ];
  }

  public static function Comments(Collection $comments)
  {
    $commentsArray = [];
    foreach ($comments as $comment) {
      $commentsArray[] = self::Comment($comment);
    }
    return $commentsArray;
  }

  public static function Event(Event $event)
  {
    $owner = $event->getOwner();

    return [
      'id' => $event->id_event,
      'title' => $event->title,
      'description' => $event->description,
      'date' => $event->date,
      'is_public' => $event->is_public,
      'links' => [
        'self' => [
          'href' => '/events/' . $event->id_event
        ],
        'owner' => [
          'href' => '/users/' . $owner->id_user
        ],
        'comments' => [
          'href' => '/events/' . $event->id_event . '/comments'
        ],
        'participants' => [
          'href' => '/events/' . $event->id_event . '/users'
        ],
      ]
    ];
  }

  public static function Events(Collection $events)
  {
    $eventsArray = [];
    foreach ($events as $event) {
      $eventsArray[] = self::Event($event);
    }
    return $eventsArray;
  }

  public static function Location(Location $location)
  {
    return [
      'id' => $location->id_location,
      'name' => $location->name,
      'lat' => $location->lat,
      'long' => $location->long,
      'is_related' => $location->is_related,
      'links' => [
        'self' => [
          'href' => '/events/' . $location->id_event . '/locations/' . $location->id_location
        ],
        'event' => [
          'href' => '/events/' . $location->id_event
        ],
      ]
    ];
  }
  public static function Locations(Collection $locations)
  {
    $locationsArray = [];
    foreach ($locations as $location) {
      $locationsArray[] = self::Location($location);
    }
    return $locationsArray;
  }
}
