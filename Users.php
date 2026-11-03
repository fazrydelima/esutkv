<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Users extends Model
{
    use HasFactory;
    protected $table = 'users';

    protected $primaryKey = 'user_id';

     public $timestamps = false;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
    ];
}
