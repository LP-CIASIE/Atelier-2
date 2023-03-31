<?php

namespace atelier\fakedata\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Location extends Model
{
  use HasUuids;

  protected $connection = 'tedyspo_db';

  protected $table = 'location';
  protected $primaryKey = 'id_location';
  protected $autoIncrement = false;
  protected $keyType = 'string';
  public $timestamps = false;
  protected $fillable = [
    'id_location',
    'name',
    'lat',
    'long',
    'is_related',
    'id_event'
  ];

  public function event()
  {
    return $this->belongsTo(Event::class, 'id_event');
  }
}
