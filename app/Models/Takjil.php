<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Takjil extends Model
{
    use HasFactory;

    protected $fillable = [
        'jamaah_id',
        'day_setting_id',
        'tanggal_hijriyah',
        'keterangan',
    ];

    // protected $casts = [
    //     'keterangan' => 'array',
    // ];

    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class);
    }

    public function daySetting()
    {
        return $this->belongsTo(DaySetting::class);
    }

    public function makanans()
    {
        return $this->hasMany(TakjilMakanan::class);
    }

    public function minumans()
    {
        return $this->hasMany(TakjilMinuman::class);
    }
}
