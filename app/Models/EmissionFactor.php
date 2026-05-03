<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmissionFactor extends Model
{
    protected $fillable = ['name', 'factor_value', 'unit', 'source', 'category_id', 'metadata'];

    protected function casts(): array
    {
        return [
            'factor_value' => 'float',
            'metadata'     => 'array',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}

