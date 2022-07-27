<?php

namespace App\Models;

use System\Database\ORM\Model;
use System\Database\Traits\HasSoftDelete;

class User extends Model
{
    use HasSoftDelete;

    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password', 'avatar', 'permission', 'bio'];
}
