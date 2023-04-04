<?php

namespace atelier\tedyspo\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Link extends Model
{
  use HasUuids;

  protected $table = 'link';
  protected $primaryKey = 'id_link';
  protected $autoIncrement = false;
  protected $keyType = 'string';
  public $timestamps = false;
  protected $fillable = [
    'id_link',
    'title',
    'link',
    'id_event'
  ];

  public function event()
  {
    return $this->belongsTo(Event::class, 'id_event');
  }
}