<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paparan extends Model
{
    use HasFactory;
    protected $table = 'paparan_awams';

    protected $primaryKey = 'paparan_id';

     public $timestamps = false;

    protected $fillable = [
        'nama_paparan',
        'jenis',
        'tarikh',
    ];
}
