<?php

namespace atelier\tedyspo\services;

use atelier\tedyspo\models\Event;
use Respect\Validation\Validator as v;
use atelier\tedyspo\models\User;

class InvitationService extends AbstractService
{

  final public function getEventsByUser($id_user)
  {
    if ($id_user == null) {
      throw new \Exception("Il manque l'id de l'utilisateur ou celui de l'événement.", 400);
    } else if (User::find($id_user) == null) {
      throw new \Exception("L'utilisateur ou l'événement n'existe pas.", 400);
    } else {
      try {
        $user = User::find($id_user);
        $events = $user->events()->get();
      } catch (\Exception $e) {
        throw new \Exception("Un problème est survenu lors de la recherche des événements", 404);
      }

      return $events;
    }
  }

  final public function getEventByUser($id_user, $id_event)
  {
    if ($id_user == null || $id_event == null) {
      throw new \Exception("Il manque l'id de l'utilisateur ou celui de l'événement.", 400);
    } else if (User::find($id_user) == null || Event::find($id_event) == null) {
      throw new \Exception("L'utilisateur ou l'événement n'existe pas.", 400);
    } else {
      try {
        $user = User::find($id_user);
        $event = $user->events()->where('event_user.id_event', $id_event)->first();
      } catch (\Exception $e) {
        throw new \Exception("Un problème est survenu lors de la recherche de l'événement", 404);
      }

      return $event;
    }
  }

  final public function createUserEvent($id_user, $id_event)
  {
    if ($id_user == null || $id_event == null) {
      throw new \Exception("Il manque l'id de l'utilisateur ou celui de l'événement.", 400);
    } else if (User::find($id_user) == null || Event::find($id_event) == null) {
      throw new \Exception("L'utilisateur ou l'événement n'existe pas.", 400);
    } else {
      try {
        $event = Event::find($id_event);
        $event->users()->attach($id_user, ['id_user' => $id_user, 'id_event' => $id_event, 'is_organisator' => false, 'state' => 'pending', 'is_here' => false, 'comment' => 'En attente de validation par l\'utilisateur']);
      } catch (\Exception $e) {
        throw new \Exception("Erreur lors de l'invitation à l'événement.'", 400);
      }
    }
    return $event;
  }

  final public function deleteUserEvent($id_event, $id_user)
  {
    if ($id_user == null || $id_event == null) {
      throw new \Exception("Il manque l'id de l'utilisateur ou celui de l'événement.", 400);
    } else if (User::find($id_user) == null || Event::find($id_event) == null) {
      throw new \Exception("L'utilisateur ou l'événement n'existe pas.", 400);
    } else {
      try {
        $event = Event::find($id_event);
        $event->users()->detach($id_user, ['id_user' => $id_user, 'id_event' => $id_event]);
      } catch (\Exception $e) {
        throw new \Exception("Erreur lors de la suppression de l'invitation à l'événement.", 400);
      }
    }
    return $event;
  }

  final public function getUserFromEvent($id_event, $id_user)
  {
    if ($id_user == null || $id_event == null) {
      throw new \Exception("Il manque l'id de l'utilisateur ou celui de l'événement.", 400);
    } else if (User::find($id_user) == null || Event::find($id_event) == null) {
      throw new \Exception("L'utilisateur ou l'événement n'existe pas.", 400);
    } else {
      try {
        $event = Event::find($id_event);
        $user = $event->users()->where('event_user.id_user', $id_user)->first();
      } catch (\Exception $e) {
        throw new \Exception("Un problème est survenu lors de la recherche de l'utilisateur", 404);
      }

      return $user;
    }
  }

  final public function getUsersFromEvent($id_event)
  {
    if ($id_event == null) {
      throw new \Exception("Il manque l'id de l'utilisateur ou celui de l'événement.", 400);
    } else if (Event::find($id_event) == null) {
      throw new \Exception("L'utilisateur ou l'événement n'existe pas.", 400);
    } else {
      try {
        $event = Event::find($id_event);
        $users = $event->users()->get();
      } catch (\Exception $e) {
        throw new \Exception("Un problème est survenu lors de la recherche des utilisateurs", 404);
      }

      return $users;
    }
  }

  final public function updateStateUserEvent($id_event, $id_user, $data)
  {
    if ($id_user == null || $id_event == null) {
      throw new \Exception("Il manque l'id de l'utilisateur ou celui de l'événement.", 400);
    } else if (User::find($id_user) == null || Event::find($id_event) == null) {
      throw new \Exception("L'utilisateur ou l'événement n'existe pas.", 400);
    } else if ($data['state'] !== 'pending' && $data['state'] !== 'accepted' && $data['state'] !== 'refused') {
      throw new \Exception("L'état de l'utilisateur n'est pas valide.", 400);
    } else {
      try {
        v::stringType()->length(0, 250)->assert($data['comment']);
      } catch (\Exception $e) {
        throw new \Exception("Le commentaire n'est pas valide.", 400);
      }
      try {
        v::stringType()->assert($data['state']);
      } catch (\Exception $e) {
        throw new \Exception("L'état de l'utilisateur n'est pas valide.", 400);
      }
      try {
        $EventUser = $this->getEventByUser($id_user, $id_event);
        $EventUser->pivot->state = $data['state'];
        $EventUser->pivot->comment = $data['comment'];
        $EventUser->pivot->save();
        $event = Event::find($id_event);
      } catch (\Exception $e) {
        throw new \Exception("Erreur lors de la mise à jour de l'état de l'utilisateur.", 400);
      }
    }
    return $EventUser;
  }
}
