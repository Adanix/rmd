<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RamadhanSetting extends Model
{
    use HasFactory;

    protected $fillable = ['start_date', 'days', 'quota_per_day', 'special_quotas', 'holidays'];

    protected $casts = [
        'special_quotas' => 'array',
        'holidays' => 'array',
    ];
}
