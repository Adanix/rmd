<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Jamaah extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'nama',
        'alamat',
        'ekonomi',
        'setoran',
        'keterangan',
        'notes',
    ];

    protected static function boot()
    {
        parent::boot();

        // otomatis generate UUID
        static::creating(function ($jamaah) {
            if (!$jamaah->uuid) {
                $jamaah->uuid = Str::uuid();
            }
        });

        // otomatis update total_setoran pada CREATE
        static::created(function () {
            self::updateTotalSetoran();
        });

        // otomatis update total_setoran pada UPDATE
        static::updated(function () {
            self::updateTotalSetoran();
        });

        // otomatis update total_setoran pada DELETE
        static::deleted(function () {
            self::updateTotalSetoran();
        });
    }

    /**
     * Hitung ulang total setoran dan simpan ke tabel ramadhan_settings
     */
    protected static function updateTotalSetoran()
    {
        $total = self::sum('setoran');

        $setting = RamadhanSetting::first();
        if ($setting) {
            $setting->update(['total_setoran' => $total]);
        }
    }

    public function takjils()
    {
        return $this->hasMany(Takjil::class);
    }
}
