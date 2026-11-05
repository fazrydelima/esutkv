<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Skema_Mata extends Model
{
    use HasFactory;
    protected $table = 'skema_matas';

    protected $primaryKey = 'skema_id';

     public $timestamps = false;

    protected $fillable = [
        'acara_id',
        'kedudukan',
        'mata',
    ];

    public function acara(){
        return $this->belongsTo(
            Acara::class,
            'acara_id',
            'acara_id'
        );
    }
}