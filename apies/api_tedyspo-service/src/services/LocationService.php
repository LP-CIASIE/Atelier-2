<?php

namespace atelier\tedyspo\services;

use atelier\tedyspo\models\Location;
use Ramsey\Uuid\Uuid;
use Respect\Validation\Validator as v;

class LocationService extends AbstractService
{
    final public function createLocation($data, $id_event)
    {
        $location = new Location();
        $location->id_location = Uuid::uuid4();

        try {

            v::stringType()->length(3, 100)->assert($data['name']);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la création du titre", 400);
        }
        $location->name = $data['name'];
        try {
            v::floatType()->validate($data['lat']);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la création de la latitude", 400);
        }
        $location->lat = $data['lat'];


        try {
            v::floatType()->validate($data['long']);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la longitude", 400);
        }
        $location->long = $data['long'];
        try {
            v::between(0, 1)->validate($data['is_related']);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la création de la visibilité", 400);
        }
        $location->is_related = $data['is_related'];
        try {
            v::uuid()->validate($id_event);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la crétion de l'event lié", 400);
        }
        $location->id_event = $id_event;


        try {
            $location->save();
        } catch (\Exception $e) {
            echo ($e->getMessage());
            throw new \Exception('Erreur lors de la création de la Location', 500);
        }

        return $location;
    }

    final public function getLocations($id_event)
    {
        try {
            v::uuid()->validate($id_event);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la récupération des locations", 400);
        }

        try {
            $locations = Location::where('id_event', $id_event)->get();
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la récupération des locations", 400);
        }

        return $locations;
    }

    final public function getLocation($id_event, $id_location)
    {

        $location = Location::where('id_event', $id_event)->where('id_location', $id_location)->first();

        return $location;
    }

    final public function deleteLocation($id_event, $id_location)
    {
        try {
            v::uuid()->validate($id_event);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la récupération des locations", 400);
        }
        try {
            v::uuid()->validate($id_location);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la récupération des locations", 400);
        }
        try {
            $location = Location::where('id_event', $id_event)->where('id_location', $id_location)->firstOrFail();
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la récupération d'une location", 400);
        }
        $location->delete();

        return $location;
    }

    final public function updateLocation($data, $id_event, $id_location)
    {


        $locations = Location::where('id_event', $id_event)->where('id_location', $id_location)->first();

        try {
            v::stringType()->length(3, 100)->assert($data['name']);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la création du titre", 400);
        }

        $locations->name = $data['name'];

        try {
            v::floatType()->validate($data['lat']);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la création de la latitude", 400);
        }


        $locations->lat = $data['lat'];

        try {
            v::floatType()->validate($data['long']);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la longitude", 400);
        }

        $locations->long = $data['long'];

        try {
            v::between(0, 1)->validate($data['is_related']);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la création de la visibilité", 400);
        }

        $locations->is_related = $data['is_related'];

        try {
            v::uuid()->validate($id_event);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la crétion de l'event lié", 400);
        }

        $locations->id_event = $id_event;
        try {
            $locations->save();
        } catch (\Exception $e) {
            echo ($e->getMessage());
            throw new \Exception('Erreur lors de la création de la Location', 500);
        }

        return $locations;
    }
}
