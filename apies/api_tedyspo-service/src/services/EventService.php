<?php

namespace atelier\tedyspo\services;

use Ramsey\Uuid\Uuid;
use atelier\tedyspo\models\Event;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
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

        $userService = $this->container->get('service.user');

        $user = $userService->getUserById($id_user);

        try {
            $event = $user->events()
                ->orderBy('event.date', 'desc')
                ->skip(($params['page'] - 1) * $params['size'])
                ->take($params['size'])
                ->get();
        } catch (\Exception $e) {
            echo ($e->getMessage());
            throw new \Exception('Erreur lors de la récupérations de tout les évenements', 500);
        }

        return $event;
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

        try {
            $user->events()->save($event, ['is_organisator' => 1, 'state' => 'accepted', 'is_here' => 0, 'comment' => '']);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la sauvegarde d'un évènement", 500);
        }

        return $event;
    }

    final public function deleteEvent($id)
    {
        try {
            $event = Event::find($id);
            $event->delete();
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la suppression d'un évènement", 400);
        }
        return $event;
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

    public function getCount($id_user): int
    {
        try {
            $user = User::findOrFail($id_user);
        } catch (\Exception $e) {
            throw new \Exception('Utilisateur introuvable', 404);
        }

        return $user->events()->count();
    }
}
