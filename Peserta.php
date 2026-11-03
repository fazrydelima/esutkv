<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Peserta extends Model
{
    use HasFactory;
    protected $table = 'peserta';

    protected $primaryKey = 'peserta_id';

     public $timestamps = false;

    protected $fillable = [
        'rumah_id',
        'acara_id',
        'nama',
    ];

    public function rumah_sukan(){
        return $this->belongsTo(
            Rumah_Sukan::class,
            'rumah_id',
            'rumah_id'
        );
    }

    public function acara(){
       return $this->belongsTo(
            Acara::class,
            'acara_id',
            'acara_id'
        ); 
    }
}
