<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'merchant_name',
        'amount',
        'category_id',
        'type',
        'distance_km',
        'co2e',
        'transacted_at',
    ];

    protected function casts(): array
    {
        return [
            'amount'      => 'float',
            'distance_km' => 'float',
            'co2e'        => 'float',
            'transacted_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
