<?php

namespace atelier\tedyspo\services;

use Ramsey\Uuid\Uuid;
use atelier\tedyspo\models\Event;
use Carbon\Carbon;
use Respect\Validation\Validator as v;
use atelier\tedyspo\models\User;

class EventService extends AbstractService
{

    final public function getEvents($id_user, $params)
    {

        if (isset($params['size'])) {
            try {
                v::key('size', v::intVal()->positive())->assert($params);
            } catch (\Exception $e) {
                throw new \Exception('Données pour la pagination invalides', 400);
            }
        } else {
            $params['size'] = 10;
        }

        if (isset($params['page'])) {
            try {
                v::key('page', v::intVal()->positive())->assert($params);
            } catch (\Exception $e) {
                throw new \Exception('Données pour la pagination invalides', 400);
            }
        } else {
            $params['page'] = 1;
        }

        if (isset($params['filter'])) {
            try {
                v::key('filter', v::stringType()->length(1, 10))->assert($params);
            } catch (\Exception $e) {
                throw new \Exception('Données pour le filtre invalides', 400);
            }
        } else {
            $params['filter'] = 'none';
        }

        $userService = $this->container->get('service.user');

        $user = $userService->getUserById($id_user);

        $request = $user->events()
            ->orderBy('event.date', 'desc')
            ->skip(($params['page'] - 1) * $params['size'])
            ->take($params['size']);

        if ($params['filter'] === 'accepted') {
            $request = $request->wherePivot('state', 'accepted');
        } elseif ($params['filter'] === 'pending') {
            $request = $request->wherePivot('state', 'pending');
        } elseif ($params['filter'] === 'refused') {
            $request = $request->wherePivot('state', 'refused');
        }

        try {
            $events = $request->get();
        } catch (\Exception $e) {
            throw new \Exception('Erreur lors de la récupération des événements', 500);
        }


        return $events;
    }
    final public function getEventById($id)
    {
        try {
            v::uuid()->assert($id);
        } catch (\Exception $e) {
            throw new \Exception("Format de l'id de l'événement incorrect", 400);
        }

        try {
            $event = Event::findOrFail($id);
        } catch (\Exception $e) {
            throw new \Exception("L'événement n'éxiste pas ou plus.", 404);
        }

        return  $event;
    }

    final public function createEvent($id_user, $data)
    {
        $event = new Event();
        $event->id_event = Uuid::uuid4();

        $userService = $this->container->get('service.user');
        $user = $userService->getUserById($id_user);

        try {
            v::stringType()->length(3, 80)->assert($data['title']);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la création du titre", 400);
        }
        $event->title = $data['title'];

        try {
            v::stringType()->length(3, 1000)->assert($data['description']);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la création de la description", 400);
        }
        $event->description = $data['description'];

        try {
            v::intVal()->assert($data['date']);
            $event->date = Carbon::createFromTimestamp($data['date']);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la création de la date", 400);
        }

        try {
            v::intVal()->length(1, 1)->assert($data['is_public']);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la création de la visibilité", 400);
        }
        $event->is_public = $data['is_public'];

        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

        $event->code_share = substr(str_shuffle($alphabet), 0, 5);

        try {
            $user->events()->save($event, ['is_organisator' => 1, 'state' => 'accepted', 'is_here' => 0, 'comment' => '']);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la sauvegarde d'un évènement", 500);
        }

        return $event;
    }

    final public function deleteEvent($id)
    {
        $event = $this->getEventById($id);

        try {
            $event->delete();
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la suppression d'un évènement", 400);
        }
    }

    final public function updateEvent($id_event, $data)
    {

        $event = $this->getEventById($id_event);

        try {
            v::stringType()->length(3, 80)->assert($data['title']);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la modfication du titre", 400);
        }
        $event->title = $data['title'];

        try {
            v::stringType()->length(3, 1000)->assert($data['description']);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la modfication de la description", 400);
        }
        $event->description = $data['description'];

        try {
            v::intVal()->length(1, 1)->assert($data['is_public']);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la modfication de la visibilité", 400);
        }
        $event->is_public = $data['is_public'];

        try {
            v::intVal()->assert($data['date']);
            $event->date = Carbon::createFromTimestamp($data['date']);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la création de la date", 400);
        }

        try {
            $event->save();
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la sauvegarde de l'évènement", 400);
        }

        return $event;
    }

    public function getCount($id_user, $filter): int
    {
        try {
            $user = User::findOrFail($id_user);
        } catch (\Exception $e) {
            throw new \Exception('Utilisateur introuvable', 404);
        }
        if ($filter === 'accepted') {
            return $user->events()->wherePivot('state', 'accepted')->count();
        } elseif ($filter === 'pending') {
            return $user->events()->wherePivot('state', 'pending')->count();
        } elseif ($filter === 'refused') {
            return $user->events()->wherePivot('state', 'refused')->count();
        } elseif ($filter === '') {
            return $user->events()->count();
        }
    }
}
