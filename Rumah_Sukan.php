<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Rumah_Sukan extends Model
{
    use HasFactory;

    protected $table = 'rumah_sukans';
    protected $primaryKey = 'id'; 

    protected $fillable = [
        'nama_rumah',
        'warna',
        'jumlah_mata',
    ];

    public function setNamaRumahAttribute(string $value): void
    {
        $this->attributes['nama_rumah'] = Str::upper($value);
    }

    public function pesertas()
    {
        return $this->hasMany(Peserta::class, 'rumah_sukan_id', 'id');
    }

    public function keputusanAcara()
    {
        return $this->hasMany(KeputusanAcara::class, 'rumah_sukan_id', 'id');
    }
}