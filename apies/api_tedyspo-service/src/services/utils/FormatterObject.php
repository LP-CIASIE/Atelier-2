<?php

namespace atelier\tedyspo\services\utils;

use atelier\tedyspo\models\Comment;
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
      'comment' => $comment->content,
      'links' => [
        'self' => [
          'href' => '/comments/' . $comment->id_comment
        ],
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
}
