<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // 6 kategori sesuai index.html (IPCC methodology)
        $categories = [
            [
                'slug'            => 'bahan_bakar',
                'name'            => 'Bahan Bakar',
                'emission_factor' => 2.33, // default (Pertalite/Pertamax kg CO2e/liter)
                'unit'            => 'liter',
                'description'     => 'Minyak tanah, LPG, solar, bensin rumah tangga',
            ],
            [
                'slug'            => 'elektronik',
                'name'            => 'Elektronik',
                'emission_factor' => 0.87, // kg CO2e/kWh (PLN grid)
                'unit'            => 'kwh',
                'description'     => 'Penggunaan perangkat elektronik rumah tangga',
            ],
            [
                'slug'            => 'penerbangan',
                'name'            => 'Penerbangan',
                'emission_factor' => 0.15, // kg CO2e/km (Ekonomi, DEFRA)
                'unit'            => 'km',
                'description'     => 'Perjalanan udara domestik/internasional',
            ],
            [
                'slug'            => 'makanan',
                'name'            => 'Makanan',
                'emission_factor' => 4.8, // default (telur, kg CO2e/kg food)
                'unit'            => 'kg_food',
                'description'     => 'Konsumsi bahan makanan sehari-hari',
            ],
            [
                'slug'            => 'sampah',
                'name'            => 'Sampah',
                'emission_factor' => 6.0, // default plastik kg CO2e/kg (IPCC Waste)
                'unit'            => 'kg',
                'description'     => 'Sampah plastik, kertas/karton',
            ],
            [
                'slug'            => 'kendaraan',
                'name'            => 'Kendaraan',
                'emission_factor' => 2.33, // default bensin kg CO2e/liter
                'unit'            => 'km',
                'description'     => 'Kendaraan pribadi berbahan bakar atau listrik',
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}

