<?php

namespace atelier\tedyspo\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Event extends Model
{
  use HasUuids;

  protected $table = 'event';
  protected $primaryKey = 'id_event';
  protected $autoIncrement = false;
  protected $keyType = 'string';
  public $timestamps = false;
  protected $fillable = [
    'id_event',
    'title',
    'description',
    'date',
    'is_public'
  ];

  public function users()
  {
    return $this->belongsToMany(UserTedyspo::class, 'event_user', 'id_event', 'id_user');
  }

  public function eventsAdditional()
  {
    return $this->belongsToMany(Event::class, 'event_event', 'id_main_event', 'id_additional_event');
  }

  public function eventsMain()
  {
    return $this->belongsToMany(Event::class, 'event_event', 'id_additional_event', 'id_main_event');
  }

  public function getOwner(): User
  {
    return $this->users()->wherePivot('is_organisator', 1)->first();
  }
}
