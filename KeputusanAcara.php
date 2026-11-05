<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KeputusanAcara extends Model
{
    protected $table = 'keputusan_acaras';

    protected $fillable = [
        'acara_id',
        'peserta_id',
        'rumah_sukan_id',
        'pingat',
        'mata',
        'status',
        'submitted_by',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    public function acara(): BelongsTo
    {
        return $this->belongsTo(Acara::class);
    }

    public function peserta(): BelongsTo
    {
        return $this->belongsTo(Peserta::class);
    }

    public function rumahSukan(): BelongsTo
    {
        return $this->belongsTo(Rumah_Sukan::class, 'rumah_sukan_id');
    }

    public function penghantar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }
}
