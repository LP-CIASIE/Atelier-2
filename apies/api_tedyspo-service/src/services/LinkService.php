<?php

namespace atelier\tedyspo\services;

use atelier\tedyspo\models\Link;
use atelier\tedyspo\models\Event;
use Respect\Validation\Validator as v;
use Illuminate\Database\Eloquent\Collection;


class LinkService extends AbstractService
{
    public function getCount($id_event)
    {
      try {
        $event = Event::findOrFail($id_event);
      } catch (\Exception $e) {
        throw new \Exception('Cette événement n\'existe pas', 404);
      }
  
      return $event->links()->count();
    }

    public function createLink($data, $id_event) : Link
    {
        
        $link = new Link();
        $link->id_link = \Ramsey\Uuid\Uuid::uuid4();
        
        try {
            $event = Event::findOrFail($id_event);
            $link->id_event = $id_event;
        } catch (\Exception $e) {
            throw new \Exception('Identifiant de l\'événement invalide', 400);
        }

        if($event->links()->count() >= 5){
            throw new \Exception('Vous avez atteint le nombre maximum de liens pour cet événement (max. 5)', 400);
        }

        try{
            v::stringVal()->length(1, 80)->assert($data['title']);
            $link->title = $data['title'];
        } catch (\Exception $e){
            throw new \InvalidArgumentException('Le titre n\'est pas valide', 400);
        }

        try{
            v::url()->length(1,750)->assert($data['link']);
            $link->link = $data['link'];
        } catch (\Exception $e){
            throw new \InvalidArgumentException('Le lien n\'est pas valide.', 400);
        }

        try{
            $link->save();
        } catch (\Exception $e){
            echo ($e->getMessage());
            throw new \InvalidArgumentException('Erreur lors de la création du lien.', 500);
        }

        return $link;
    }

    public function getLinks($id_event) : Collection
    {
        try{
            v::uuid()->assert($id_event);
        } catch (\Exception $e){
            throw new \InvalidArgumentException('L\'id de l\'événement n\'est pas valide.', 400);
        }

        try {
            $event = Event::findOrFail($id_event);
        } catch (\Exception $e) {
            throw new \Exception('Cette événement n\'existe pas ou plus', 404);
        }

        try{
            $links = $event->links()->get();
        } catch (\Exception $e){
            throw new \InvalidArgumentException('Erreur lors de la récupération des liens.', 500);
        }

        return $links;
    }

    public function getLink($id_link) : Link
    {
        try{
            v::uuid()->assert($id_link);
        } catch (\Exception $e){
            throw new \InvalidArgumentException('L\'id du lien n\'est pas valide.', 400);
        }

        try{
            $link = Link::findOrfail($id_link);
        } catch (\Exception $e){
            throw new \InvalidArgumentException('Lien introuvable', 404);
        }

        return $link;
    }

    public function updateLink($data, $id_link) : Link
    {
        try{
            $link = Link::findOrFail($id_link);
        } catch (\Exception $e){
            throw new \InvalidArgumentException('Lien introuvable', 404);
        }


        if (isset($data['title'])){
            try{
                v::stringVal()->length(1, 80)->assert($data['title']);
                $link->title = $data['title'];
            } catch (\Exception $e){
                throw new \InvalidArgumentException('Le titre n\'est pas valide', 400);
            }
        }

        if(isset($data['link'])){
            try{
                v::url()->length(1,750)->assert($data['link']);
                $link->link = $data['link'];
            } catch (\Exception $e){
                throw new \InvalidArgumentException('Le lien n\'est pas valide.', 400);
            }
        }

        try{
            $link->save();
        } catch (\Exception $e){
            throw new \InvalidArgumentException('Erreur lors de la mise à jour du lien.', 500);
        }

        return $link;
    }

    public function deleteLink($id_link) : void
    {
        try{
            v::uuid()->assert($id_link);
        } catch (\Exception $e){
            throw new \InvalidArgumentException('L\'id du lien n\'est pas valide.', 400);
        }

        $link = $this->getLink($id_link);

        try{
            $link->delete();
        } catch (\Exception $e){
            throw new \InvalidArgumentException('Erreur lors de la suppression du lien.', 500);
        }
    }
}
