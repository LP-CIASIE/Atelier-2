<?php

namespace atelier\fakedata\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UserAuth extends Model
{
  use HasUuids;

  protected $connection = 'auth_db';

  protected $table = 'user';
  protected $primaryKey = 'id_user';
  protected $autoIncrement = false;
  protected $keyType = 'string';
  public $timestamps = false;
  protected $fillable = [
    'id_user',
    'email',
    'password',
    'role',
    'refresh_token'
  ];
}
