<?php

namespace atelier\tedyspo\services\utils;

use atelier\tedyspo\models\Comment;
use atelier\tedyspo\models\Event;
use atelier\tedyspo\models\User;
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
        'locations' => [
          'href' => '/events/' . $event->id_event . '/locations'
        ],
        'urls' => [
          'href' => '/events/' . $event->id_event . '/links'
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

  public static function EventUser($eventUser)
  {

    return [
      'state' => $eventUser->state,
      'comment' => $eventUser->comment,
      'is_here' => $eventUser->is_here,
      'is_organisator' => $eventUser->is_organisator,
      'links' => [
        'self' => [
          'href' => '/events/' . $eventUser->id_event . '/users/' . $eventUser->id_user
        ],
        'user' => [
          'href' => '/users/' . $eventUser->id_user
        ],
        'event' => [
          'href' => '/events/' . $eventUser->id_event
        ],
      ]
    ];
  }

  public static function EventUsers($eventUsers)
  {
    $eventUsersArray = [];
    foreach ($eventUsers as $eventUser) {

      $eventUsersArray[] = self::EventUser($eventUser->pivot);
    }
    return $eventUsersArray;
  }
}
