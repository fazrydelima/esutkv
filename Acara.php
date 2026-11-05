<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Acara extends Model
{
    use HasFactory;

    protected $table = 'acaras';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_acara',
        'kategori',
    ];

    public function setNamaAcaraAttribute(string $value): void
    {
        $this->attributes['nama_acara'] = Str::upper($value);
    }

    public function setKategoriAttribute(string $value): void
    {
        $this->attributes['kategori'] = Str::upper($value);
    }

    public function keputusans()
    {
        return $this->hasMany(Keputusan::class, 'acara_id', 'id');
    }
}
