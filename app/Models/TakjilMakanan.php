<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TakjilMakanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'takjil_id',
        'makanan_id',
        'jumlah',
    ];

    public function takjil()
    {
        return $this->belongsTo(Takjil::class);
    }

    public function makanan()
    {
        return $this->belongsTo(Makanan::class);
    }
}
