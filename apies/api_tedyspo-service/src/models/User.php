<?php

namespace atelier\tedyspo\models;

// I use Eloquent with UUID of Ramsey/Uuid

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class User extends Model
{
  use HasUuids;

  protected $table = 'user';
  protected $primaryKey = 'user_id';
  public $timestamps = false;
}
