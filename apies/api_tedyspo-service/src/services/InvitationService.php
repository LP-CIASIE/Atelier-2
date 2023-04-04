<?php

namespace atelier\tedyspo\services;

use Respect\Validation\Validator as v;
use Psr\Container\ContainerInterface;

class InvitationService extends AbstractService
{

  private $eventService;
  private $userService;

  public function __construct(ContainerInterface $container)
  {
    parent::__construct($container);
    $this->eventService = $this->container->get('service.event');
    $this->userService = $this->container->get('service.user');
  }

  final public function getUsersEvent($id_event)
  {

    $event = $this->eventService->getEventById($id_event);
    try {
      $usersEvent = $event->users()->withPivot('state', 'comment', 'is_here', 'is_organisator')->get();
    } catch (\Exception $e) {
      throw new \Exception("Un problème est survenu lors de la recherche des utilisateurs", 404);
    }
    return $usersEvent;
  }

  final public function getEventsUser($id_user)
  {
    $user = $this->userService->getUserById($id_user);
    try {
      $events = $user->events()->get();
    } catch (\Exception $e) {
      throw new \Exception("Un problème est survenu lors de la recherche des événements", 404);
    }
    return $events;
  }

  final public function getEventUser($id_user, $id_event)
  {
    $user = $this->userService->getUserById($id_user);
    $this->eventService->getEventById($id_event);
    try {
      $eventUser = $user->events()->where('event_user.id_event', $id_event)->withPivot('state', 'comment', 'is_here', 'is_organisator')->first()->pivot;
      if ($eventUser === null) {
        throw new \Exception("L'utilisateur n'est pas invité à cet événement.", 400);
      }
    } catch (\Exception $e) {
      throw new \Exception("Un problème est survenu lors de la recherche de l'événement", 404);
    }
    return $eventUser;
  }

  final public function createEventUser($id_user, $id_event)
  {
    $this->userService->getUserById($id_user);
    $event = $this->eventService->getEventById($id_event);
    try {
      $event->users()->attach($id_user, ['is_organisator' => false, 'state' => 'pending', 'is_here' => false, 'comment' => 'En attente de validation par l\'utilisateur']);
    } catch (\Exception $e) {
      throw new \Exception("Erreur lors de l'invitation à l'événement.'", 400);
    }
    return $event;
  }

  final public function deleteEventUser($id_event, $id_user)
  {
    $this->userService->getUserById($id_user);
    $event = $this->eventService->getEventById($id_event);
    try {
      $event->users()->detach($id_user);
    } catch (\Exception $e) {
      throw new \Exception("Erreur lors de la suppression de l'invitation à l'événement.", 400);
    }
    return $event;
  }

  // A gérer : uniquement l'organisateur ainsi que l'utilisateur concerné peut modifier l'état

  final public function updateEventUser($id_event, $id_user, $data)
  {
    $this->userService->getUserById($id_user);
    $this->eventService->getEventById($id_event);
    if ($data['state'] !== 'pending' && $data['state'] !== 'accepted' && $data['state'] !== 'refused') {
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
        $eventUser = $this->getEventUser($id_user, $id_event);
        $eventUser->state = $data['state'];
        $eventUser->comment = $data['comment'];
        $eventUser->save();
      } catch (\Exception $e) {
        throw new \Exception("Erreur lors de la mise à jour de l'état de l'utilisateur.", 400);
      }
    }
    return $eventUser;
  }
}
