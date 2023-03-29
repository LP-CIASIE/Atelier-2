<?php

namespace atelier\fakedata\models;

use Illuminate\Database\Eloquent\Model;

class UserAuth extends Model
{
  protected $connection = 'auth_db';

  protected $table = 'user';
  protected $primaryKey = 'id';
  public $timestamps = false;
  protected $fillable = [
    'id',
    'email',
    'password',
    'salt',
    'created_at',
    'updated_at',
  ];
}
