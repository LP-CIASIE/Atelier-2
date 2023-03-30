<?php

namespace atelier\tedyspo\models;

// I use Eloquent with UUID of Ramsey/Uuid

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Event extends Model
{
  use HasUuids;
  protected $table = 'event';
  protected $primaryKey = 'id_event';
  public $timestamps = false;
}
