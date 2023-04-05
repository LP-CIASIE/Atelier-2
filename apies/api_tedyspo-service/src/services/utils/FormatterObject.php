<?php

namespace atelier\tedyspo\services\utils;

use atelier\tedyspo\models\Link;
use atelier\tedyspo\models\User;
use atelier\tedyspo\models\Event;
use atelier\tedyspo\models\Comment;
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

  public static function Comment(Comment $comment, $embed = 'none')
  {
    $data = [
      'id' => $comment->id_comment,
      'comment' => $comment->comment,
      'created_at' => $comment->created_at,
      'links' => [
        'user' => [
          'href' => '/users/' . $comment->id_user
        ],
        'event' => [
          'href' => '/events/' . $comment->id_event
        ],
      ]
    ];

    if ($embed == 'user') {
      $data['user'] = self::User($comment->user);
    }

    return $data;
  }

  public static function Comments(Collection $comments, $embed = 'none')
  {
    $commentsArray = [];
    foreach ($comments as $comment) {
      $commentsArray[] = self::Comment($comment, $embed);
    }
    return $commentsArray;
  }

  public static function Event(Event $event, $embed = 'none')
  {
    $owner = $event->getOwner();

    $data =  [
      'id' => $event->id_event,
      'title' => $event->title,
      'description' => $event->description,
      'date' => $event->date,
      'is_public' => $event->is_public,
      'code_share' => $event->code_share,
    ];

    if ($embed == 'location') {
      $locations = $event->locations;
      $data['locations'] = FormatterObject::Locations($locations);
    }

    $data['links'] = [
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
    ];
    return $data;
  }

  public static function Events(Collection $events, $embed = 'none')
  {
    $eventsArray = [];
    foreach ($events as $event) {
      $eventsArray[] = self::Event($event, $embed);
    }
    return $eventsArray;
  }

  public static function EventUser($eventUser, $embed = 'none')
  {

    if ($embed == 'user') {
      $data = self::User($eventUser);
      $data['status_event'] = [
        'state' => $eventUser->pivot->state,
        'comment' => $eventUser->pivot->comment,
        'is_here' => $eventUser->pivot->is_here,
        'is_organisator' => $eventUser->pivot->is_organisator,
        'links' => [
          'self' => [
            'href' => '/events/' . $eventUser->pivot->id_event . '/users/' . $eventUser->id_user
          ],
          'user' => [
            'href' => '/users/' . $eventUser->id_user
          ],
          'event' => [
            'href' => '/events/' . $eventUser->pivot->id_event
          ],
        ]
      ];
    } else {
      $data = [
        'id_user' => $eventUser->id_user,
        'id_event' => $eventUser->pivot->id_event,
        'state' => $eventUser->pivot->state,
        'comment' => $eventUser->pivot->comment,
        'is_here' => $eventUser->pivot->is_here,
        'is_organisator' => $eventUser->pivot->is_organisator,
        'links' => [
          'self' => [
            'href' => '/events/' . $eventUser->pivot->id_event . '/users/' . $eventUser->id_user
          ],
          'user' => [
            'href' => '/users/' . $eventUser->id_user
          ],
          'event' => [
            'href' => '/events/' . $eventUser->pivot->id_event
          ],
        ]
      ];
    }

    if ($embed == 'user') {
    }

    return $data;
  }

  public static function EventUsers($eventUsers, $embed = 'none')
  {
    $eventUsersArray = [];
    foreach ($eventUsers as $eventUser) {
      $eventUsersArray[] = self::EventUser($eventUser, $embed);
    }
    return $eventUsersArray;
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

  public static function Link(Link $link)
  {
    return [
      'id' => $link->id_link,
      'title' => $link->title,
      'url' => $link->link,
      'links' => [
        'event' => [
          'href' => '/events/' . $link->id_event
        ],
      ]
    ];
  }

  public static function Links(Collection $links)
  {
    $linksArray = [];
    foreach ($links as $link) {
      $linksArray[] = self::Link($link);
    }
    return $linksArray;
  }
}
