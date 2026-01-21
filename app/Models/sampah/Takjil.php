<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Takjil extends Model
{
    use HasFactory;

    protected $fillable = [
        'jamaah_id',
        'nama',
        'alamat',
        'tanggal_masehi',
        'tanggal_hijriyah',
        'keterangan'
    ];

    protected $casts = [
        'keterangan' => 'array',
    ];

    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class);
    }
}
