<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Peserta extends Model
{
    use HasFactory;

    protected $table = 'pesertas';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_peserta',
        'rumah_sukan_id',
        'jantina',
    ];

    public function setNamaPesertaAttribute(string $value): void
    {
        $this->attributes['nama_peserta'] = Str::upper($value);
    }

    public function rumahSukan()
    {
        return $this->belongsTo(Rumah_Sukan::class, 'rumah_sukan_id', 'id');
    }

    public function keputusans()
    {
        return $this->hasMany(Keputusan::class, 'peserta_id', 'id');
    }
}