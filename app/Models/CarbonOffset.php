<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarbonOffset extends Model
{
    protected $fillable = ['name', 'description', 'cost_per_ton', 'available_tons'];

    protected function casts(): array
    {
        return [
            'cost_per_ton'    => 'float',
            'available_tons'  => 'float',
        ];
    }
}
