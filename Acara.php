<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Acara extends Model
{
    use HasFactory;
    protected $table = 'acara';

    protected $primaryKey = 'acara_id';

     public $timestamps = false;

    protected $fillable = [
        'keputusan_id',
        'nama_acara',
        'kategori',
        'tarikh',
    ];

     public function keputusan()
    {
        return $this->hasMany(
            Keputusan::class,
            'keputusan_id',
            'keputusan_id'
        );
    }
}
