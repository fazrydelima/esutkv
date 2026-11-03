<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Keputusan extends Model
{
    use HasFactory;
    protected $table = 'keputusan';

    protected $primaryKey = 'keputusan_id';

     public $timestamps = false;

    protected $fillable = [
        'acara_id',
        'rumah_id',
        'peserta_id',
        'kedudukan',
        'mata',
        'jenis_keputusan',
    ];

    public function acara()
    {
        return $this->belongsTo(
            Acara::class,
            'acara_id',
            'acara_id'
        );
    }

    public function rumah_sukan()
    {
        return $this->belongsTo(
            Rumah_Sukan::class,
            'rumah_id',
            'rumah_id'
        );
    }

    public function peserta()
    {
        return $this->belongsTo(
            Peserta::class,
            'peserta_id',
            'peserta_id'
        );
    }
}
