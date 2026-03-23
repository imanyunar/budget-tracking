<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'symbol',
        'name',
        'asset_type',
        'lots',
        'quantity',
        'average_price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
