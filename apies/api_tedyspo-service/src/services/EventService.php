<?php

namespace atelier\tedyspo\services;
use Ramsey\Uuid\Uuid;
use atelier\tedyspo\models\Event;
use Carbon\Carbon;
use Illuminate\Database\DBAL\TimestampType;

class EventService extends AbstractService
{
    final public function getEvents()
    {
        
        return Event::all();

    }
    final public function getEventById($id)
    {
        return Event::find($id);
    }

    final public function createEvent($data)
    {
        $event = new Event();
        $event->id_event = Uuid::uuid4();
        $event->title = $data['title'];
        $event->description = $data['description'];
        $event->date = Carbon::now()->tz('Europe/Amsterdam');
        $event->is_public = $data['is_public'];
        $event->save();
        return $event;
    }

    final public function deleteEvent($id)
    {
        $event = Event::find($id);
        $event->delete();
        return $event;
    }
    
    final public function updateEvent($id, $data){
        $event = Event::find($id);
        $event->title = $data['title'];
        $event->description = $data['description'];
        $event->date = Carbon::now()->tz('Europe/Amsterdam');
        $event->is_public = $data['is_public'];
        $event->save();
        return $event;
    }
}
