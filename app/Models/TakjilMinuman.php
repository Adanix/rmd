<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TakjilMinuman extends Model
{
    use HasFactory;

    protected $table = 'takjil_minumans';

    protected $fillable = [
        'takjil_id',
        'minuman_id',
        'jumlah',
    ];

    public function takjil()
    {
        return $this->belongsTo(Takjil::class);
    }

    public function minuman()
    {
        return $this->belongsTo(Minuman::class);
    }
}
