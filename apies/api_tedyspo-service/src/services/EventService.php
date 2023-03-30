<?php

namespace atelier\tedyspo\services;
use Ramsey\Uuid\Uuid;
use atelier\tedyspo\models\Event;
use Carbon\Carbon;
use Illuminate\Database\DBAL\TimestampType;
use Illuminate\Database\Eloquent\Collection;
use Respect\Validation\Validator as v;

class EventService extends AbstractService
{
    final public function getEvents($params)
    {
        $page = $parameters['page'] ?? 1;
        $size = $parameters['size'] ?? 10;
        $email = $parameters['email'] ?? '';
        
        try{
            v::intVal()->min(1)->assert($size);}
        catch (\Exception $e){  
            throw new \InvalidArgumentException('Format de donnée pour la taille est incorrect.', 400);
        }
        try{
            v::intVal()->min(1)->assert($page);}
        catch (\Exception $e){  
            throw new \InvalidArgumentException('Format de donnée pour la page est incorrect.', 400);
        }

        try {


            $event = Event::all()
            ->orderBy('email', 'desc')
            ->skip(($page - 1) * $size)
            ->take($size);
        }catch (\Exception $e){
            throw new \Exception('Erreur lors de la récupérations de tout les évenements', 400);
        }

        return $event;

    }
    final public function getEventById($id)
    {
        try {
            
            $event = Event::find($id);
        }catch (\Exception $e){
            throw new \Exception("Erreur lors de la récupérations d'un évènement", 400);
        }

        return  $event;
    }

    final public function createEvent($data)
    {
        $event = new Event();
        $event->id_event = Uuid::uuid4();
            

        
        try{

        v::stringType()->length(3, 100)->assert($data['title']);
        }
        catch (\Exception $e){
            throw new \Exception("Erreur lors de la création du titre", 400);
        }
        $event->title = $data['title'];
             
        try{
        v::stringType()->length(3, 100)->assert($data['description']);
        }
        catch (\Exception $e){
            throw new \Exception("Erreur lors de la création de la description", 400);
        }
        $event->description = $data['description'];
        
        try{
        v::stringType()->length(3, 100)->assert($data['date']);
        }catch (\Exception $e){
            throw new \Exception("Erreur lors de la création de la date", 400);
        }
        $event->date = Carbon::now()->tz('Europe/Amsterdam');
        
        try{
        v::boolType()->assert($data['is_public']);
        }
        catch (\Exception $e){
            throw new \Exception("Erreur lors de la création de la visibilité", 400);
        }
        $event->is_public = $data['is_public'];

        try{
        $event->save();
        }
        catch (\Exception $e){
            throw new \Exception("Erreur lors de la sauvegarde d'un évènement", 400);
        }
        return $event;
    }

    final public function deleteEvent($id)
    {
        

        try{
        $event = Event::find($id);
        $event->delete();
        }catch (\Exception $e){
            throw new \Exception("Erreur lors de la suppression d'un évènement", 400);
        }
        return $event;
    }
    
    final public function updateEvent($id, $data){
        
        $event = Event::find($id);

        try{
        v::stringType()->length(3, 100)->assert($data['title']);
        }
        catch (\Exception $e){
            throw new \Exception("Erreur lors de la modfication du titre", 400);
        }
        $event->title = $data['title'];

        try{
        v::stringType()->length(3, 100)->assert($data['description']);
        }
        catch (\Exception $e){
            throw new \Exception("Erreur lors de la modfication de la description", 400);
        }
        $event->description = $data['description'];

        try{
        v::boolType()->assert($data['is_public']);
        }
        catch (\Exception $e){
            throw new \Exception("Erreur lors de la modfication de la visibilité", 400);
        }
        $event->is_public = $data['is_public'];

        try{
        $event->save();
        }
        catch (\Exception $e){
            throw new \Exception("Erreur lors de la sauvegarde de l'évènement", 400);
        }

        return $event;
    }
}
