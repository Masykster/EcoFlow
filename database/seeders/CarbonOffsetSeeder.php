<?php

namespace Database\Seeders;

use App\Models\CarbonOffset;
use Illuminate\Database\Seeder;

class CarbonOffsetSeeder extends Seeder
{
    public function run(): void
    {
        $offsets = [
            ['name' => 'Penanaman Mangrove Kalimantan',   'description' => 'Restorasi hutan mangrove di pesisir Kalimantan Timur.',        'cost_per_ton' => 125000, 'available_tons' => 500.0],
            ['name' => 'Reforestasi Sumatera',            'description' => 'Penanaman pohon di lahan kritis Sumatera Selatan.',            'cost_per_ton' => 95000,  'available_tons' => 1200.0],
            ['name' => 'Energi Surya Pedesaan Jawa',      'description' => 'Panel surya untuk desa terpencil di Jawa Tengah.',             'cost_per_ton' => 180000, 'available_tons' => 350.0],
            ['name' => 'Biogas Limbah Ternak',            'description' => 'Konversi limbah sapi menjadi energi biogas di NTT.',           'cost_per_ton' => 110000, 'available_tons' => 750.0],
        ];

        foreach ($offsets as $o) {
            CarbonOffset::updateOrCreate(['name' => $o['name']], $o);
        }
    }
}
