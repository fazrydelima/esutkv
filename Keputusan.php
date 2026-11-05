<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Keputusan extends Model
{
    use HasFactory;

    protected $table = 'keputusans';
    protected $primaryKey = 'id';

    protected $fillable = [
        'acara_id',
        'peserta_id',
        'pingat',
        'mata',
    ];

    public function acara()
    {
        return $this->belongsTo(Acara::class, 'acara_id', 'id');
    }

    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'peserta_id', 'id');
    }
}
