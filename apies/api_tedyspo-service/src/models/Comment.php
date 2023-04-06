<?php

namespace atelier\tedyspo\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Comment extends Model
{
  use HasUuids;

  protected $table = 'comment';
  protected $primaryKey = 'id_comment';
  protected $autoIncrement = false;
  protected $keyType = 'string';
  public $timestamps = false;
  protected $fillable = [
    'id_comment',
    'comment',
    'id_user',
    'id_event'
  ];

  public function user()
  {
    return $this->belongsTo(User::class, 'id_user');
  }

  public function event()
  {
    return $this->belongsTo(Event::class, 'id_event');
  }

  public function medias()
  {
    return $this->hasMany(Media::class, 'id_comment');
  }
}
