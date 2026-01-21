<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RamadhanSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'days',
        'total_setoran',
        'special_quotas',
        'holidays',
        'notes',
    ];

    protected $casts = [
        'start_date'        => 'date',
        'end_date'          => 'date',
        'special_quotas'    => 'array',
        'holidays'          => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        // Otomatis hitung total_setoran saat membuat RamadhanSetting
        static::creating(function ($model) {
            // Pastikan total_setoran dihitung otomatis hanya jika belum diisi
            if (empty($model->total_setoran) || $model->total_setoran == 0) {
                $model->total_setoran = Jamaah::sum('setoran');
            }
        });

        // Opsional: Juga update saat model di-update jika total_setoran kosong
        static::updating(function ($model) {
            // Jika total_setoran sengaja di-set ke 0 atau null, hitung ulang
            if (empty($model->total_setoran) || $model->total_setoran == 0) {
                $model->total_setoran = Jamaah::sum('setoran');
            }
        });
    }

    /**
     * Method untuk menghitung ulang total_setoran manual
     */
    public function updateTotalSetoran()
    {
        $this->total_setoran = Jamaah::sum('setoran');
        $this->save();
        return $this;
    }

    public function daySettings()
    {
        return $this->hasMany(DaySetting::class);
    }
}
