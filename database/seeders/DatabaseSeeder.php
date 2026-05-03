<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            EmissionFactorSeeder::class,
            CarbonOffsetSeeder::class,
        ]);

        User::factory()->create([
            'name'    => 'Test User',
            'email'   => 'test@example.com',
            'api_key' => Str::random(64),
        ]);
    }
}

