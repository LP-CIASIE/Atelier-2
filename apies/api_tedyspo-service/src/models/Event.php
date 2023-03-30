<?php

namespace atelier\tedyspo\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids as HasUuids;

class Event extends Model
{
  use HasUuids;
  protected $table = 'event';
  protected $primaryKey = 'id_event';
  public $timestamps = false;
}
