<?php

namespace atelier\tedyspo\services;
use atelier\tedyspo\models\Location;
use Ramsey\Uuid\Uuid;
use Respect\Validation\Validator as v;

class LocationService extends AbstractService
{
    final public function createLocation($data,$id_event)
    {
        $location = new Location();
        $location->id_location = Uuid::uuid4();
            
        try{

        v::stringType()->length(3, 100)->assert($data['name']);
        }
        catch (\Exception $e){
            throw new \Exception("Erreur lors de la création du titre", 400);
        }
        $location->name = $data['name'];
             
        try{
            v::decimals(2)->validate($data['lat']);
        }catch (\Exception $e){
            throw new \Exception("Erreur lors de la création de la latitude", 400);
        }
        $location->lat = $data['lat'];
        

        try{
            v::decimals(2)->validate($data['long']);

        }catch (\Exception $e){
            throw new \Exception("Erreur lors de la création de la date", 400);
        }
        $location->long = $data['long'];
        try{
            v::between(0, 1)->validate($data['is_related']);
        }
        catch (\Exception $e){
            throw new \Exception("Erreur lors de la création de la visibilité", 400);
        }
        $location->is_related = $data['is_related'];
        try{
            v::uuid()->validate($data['id_event']);
        }
        catch (\Exception $e){
            throw new \Exception("Erreur lors de la création de l'event", 400);
        }
        $location->id_event = $id_event;
    }
}