<?php

namespace atelier\fakedata\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UserTedyspo extends Model
{
  use HasUuids;

  protected $connection = 'tedyspo_db';

  protected $table = 'user';
  protected $primaryKey = 'id_user';
  protected $autoIncrement = false;
  protected $keyType = 'string';
  public $timestamps = false;
  protected $fillable = [
    'id_user',
    'email',
    'firstname',
    'lastname',
  ];

  public function events()
  {
    return $this->belongsToMany(Event::class, 'user_event', 'id_user', 'id_event');
  }
}
