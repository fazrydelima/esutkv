<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rumah_Sukan extends Model
{
    use HasFactory;
    protected $table = 'rumah_sukan';

    protected $primaryKey = 'rumah_id';

     public $timestamps = false;

    protected $fillable = [
        'peserta_id',
        'nama_rumah',
        'warna',
    ];

    public function peserta()
    {
        return $this->hasMany(
            Peserta::class,
            'peserta_id',
            'peserta_id'
        );
    }
}
