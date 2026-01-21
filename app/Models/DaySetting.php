<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'ramadhan_setting_id',
        'date',
        'quota',
        'total_makanan_planned',
        'total_minuman_planned',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Tambahkan appends jika ingin menggunakan accessor di response
    protected $appends = ['quota_status', 'is_quota_full', 'filled_count'];

    public function ramadhanSetting()
    {
        return $this->belongsTo(RamadhanSetting::class);
    }

    public function takjils()
    {
        return $this->hasMany(Takjil::class);
    }

    /**
     * Get filled count
     */
    public function getFilledCountAttribute(): int
    {
        return $this->takjils_count ?? $this->takjils()->count();
    }

    /**
     * Get filled quota status
     */
    public function getQuotaStatusAttribute(): string
    {
        $filled = $this->filled_count;
        $total = $this->quota;

        return "{$filled}/{$total}";
    }

    /**
     * Get quota percentage
     */
    public function getQuotaPercentageAttribute(): float
    {
        if ($this->quota <= 0) {
            return 0;
        }

        return min(100, ($this->filled_count / $this->quota) * 100);
    }

    /**
     * Check if quota is full
     */
    public function getIsQuotaFullAttribute(): bool
    {
        return $this->filled_count >= $this->quota;
    }

    /**
     * Get formatted date
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->date->format('d M Y');
    }
}
