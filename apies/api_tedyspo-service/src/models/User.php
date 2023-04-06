<?php

namespace atelier\tedyspo\models;

// I use Eloquent with UUID of Ramsey/Uuid

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class User extends Model
{
  use HasUuids;

  protected $table = 'user';
  protected $primaryKey = 'id_user';
  public $timestamps = false;

  public function comments()
  {
    return $this->hasMany(Comment::class, 'id_user');
  }

  public function events()
  {
    return $this->belongsToMany(Event::class, 'event_user', 'id_user', 'id_event');
  }
}
