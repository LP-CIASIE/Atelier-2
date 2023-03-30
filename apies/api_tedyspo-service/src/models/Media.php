<?php

namespace atelier\tedyspo\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Media extends Model
{
  use HasUuids;

  protected $table = 'media';
  protected $primaryKey = 'id_media';
  protected $autoIncrement = false;
  protected $keyType = 'string';
  public $timestamps = false;
  protected $fillable = [
    'id_media',
    'path',
    'type',
    'id_comment'
  ];

  public function comment()
  {
    return $this->belongsTo(Comment::class, 'id_comment');
  }
}
