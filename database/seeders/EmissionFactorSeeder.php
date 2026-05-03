<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\EmissionFactor;
use Illuminate\Database\Seeder;

class EmissionFactorSeeder extends Seeder
{
    public function run(): void
    {
        $efListrik = 0.87; // kg CO2e/kWh PLN

        $data = [

            // ── 1. Bahan Bakar (kg CO2e per Liter) ──────────────────────────
            'bahan_bakar' => [
                ['name' => 'Minyak Tanah',      'factor_value' => 2.52, 'unit' => 'kg CO2e/liter', 'source' => 'IPCC 2006 Vol2'],
                ['name' => 'Minyak Residu',     'factor_value' => 3.11, 'unit' => 'kg CO2e/liter', 'source' => 'IPCC 2006 Vol2'],
                ['name' => 'LPG',               'factor_value' => 1.53, 'unit' => 'kg CO2e/liter', 'source' => 'IPCC 2006 Vol2'],
                ['name' => 'Diesel / Solar',    'factor_value' => 2.68, 'unit' => 'kg CO2e/liter', 'source' => 'IPCC 2006 Vol2'],
                ['name' => 'Biosolar (B35)',    'factor_value' => 1.74, 'unit' => 'kg CO2e/liter', 'source' => 'IPCC 2006 Vol2'],
                ['name' => 'Pertamax',          'factor_value' => 2.33, 'unit' => 'kg CO2e/liter', 'source' => 'IPCC 2006 Vol2'],
                ['name' => 'Pertalite',         'factor_value' => 2.33, 'unit' => 'kg CO2e/liter', 'source' => 'IPCC 2006 Vol2'],
                ['name' => 'Pertamax Turbo',    'factor_value' => 2.33, 'unit' => 'kg CO2e/liter', 'source' => 'IPCC 2006 Vol2'],
                ['name' => 'Pertamax Green',    'factor_value' => 2.20, 'unit' => 'kg CO2e/liter', 'source' => 'IPCC 2006 Vol2'],
            ],

            // ── 2. Elektronik (watt stored in metadata, EF = PLN grid) ──────
            'elektronik' => [
                ['name' => 'Setrika',           'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'source' => 'ESDM RI', 'metadata' => ['watt' => 350]],
                ['name' => 'Mesin Cuci',        'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'source' => 'ESDM RI', 'metadata' => ['watt' => 300]],
                ['name' => 'Dispenser',         'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'source' => 'ESDM RI', 'metadata' => ['watt' => 350]],
                ['name' => 'Kipas Angin',       'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'source' => 'ESDM RI', 'metadata' => ['watt' => 50]],
                ['name' => 'Komputer (PC)',      'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'source' => 'ESDM RI', 'metadata' => ['watt' => 200]],
                ['name' => 'Laptop',            'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'source' => 'ESDM RI', 'metadata' => ['watt' => 50]],
                ['name' => 'Kulkas',            'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'source' => 'ESDM RI', 'metadata' => ['watt' => 100]],
                ['name' => 'Printer',           'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'source' => 'ESDM RI', 'metadata' => ['watt' => 30]],
                ['name' => 'Televisi',          'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'source' => 'ESDM RI', 'metadata' => ['watt' => 100]],
                ['name' => 'Rice Cooker',       'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'source' => 'ESDM RI', 'metadata' => ['watt' => 350]],
                ['name' => 'Kompor Listrik',    'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'source' => 'ESDM RI', 'metadata' => ['watt' => 1000]],
                ['name' => 'Blender',           'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'source' => 'ESDM RI', 'metadata' => ['watt' => 300]],
                ['name' => 'Oven Listrik',      'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'source' => 'ESDM RI', 'metadata' => ['watt' => 1000]],
                ['name' => 'Microwave',         'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'source' => 'ESDM RI', 'metadata' => ['watt' => 800]],
                ['name' => 'Vacuum Cleaner',    'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'source' => 'ESDM RI', 'metadata' => ['watt' => 600]],
                ['name' => 'Water Heater',      'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'source' => 'ESDM RI', 'metadata' => ['watt' => 800]],
            ],

            // ── 3. Penerbangan (kg CO2e per km, DEFRA with RFI) ────────────
            'penerbangan' => [
                ['name' => 'Ekonomi',           'factor_value' => 0.15, 'unit' => 'kg CO2e/km', 'source' => 'DEFRA (incl. RFI)'],
                ['name' => 'Bisnis/First Class', 'factor_value' => 0.45, 'unit' => 'kg CO2e/km', 'source' => 'DEFRA (incl. RFI)'],
            ],

            // ── 4. Makanan (kg CO2e per kg food, Poore & Nemecek 2018) ──────
            'makanan' => [
                ['name' => 'Telur',                   'factor_value' => 4.8,  'unit' => 'kg CO2e/kg', 'source' => 'Poore & Nemecek 2018'],
                ['name' => 'Susu',                    'factor_value' => 3.2,  'unit' => 'kg CO2e/kg', 'source' => 'Poore & Nemecek 2018'],
                ['name' => 'Ikan',                    'factor_value' => 5.1,  'unit' => 'kg CO2e/kg', 'source' => 'Poore & Nemecek 2018'],
                ['name' => 'Beras',                   'factor_value' => 4.5,  'unit' => 'kg CO2e/kg', 'source' => 'Poore & Nemecek 2018'],
                ['name' => 'Seafood (Udang/Kerang)',  'factor_value' => 26.9, 'unit' => 'kg CO2e/kg', 'source' => 'Poore & Nemecek 2018'],
                ['name' => 'Unggas (Ayam/Bebek)',     'factor_value' => 9.9,  'unit' => 'kg CO2e/kg', 'source' => 'Poore & Nemecek 2018'],
                ['name' => 'Daging Domba',            'factor_value' => 39.7, 'unit' => 'kg CO2e/kg', 'source' => 'Poore & Nemecek 2018'],
                ['name' => 'Daging Sapi',             'factor_value' => 99.5, 'unit' => 'kg CO2e/kg', 'source' => 'Poore & Nemecek 2018'],
                ['name' => 'Daging Babi',             'factor_value' => 12.3, 'unit' => 'kg CO2e/kg', 'source' => 'Poore & Nemecek 2018'],
                ['name' => 'Keju',                    'factor_value' => 23.9, 'unit' => 'kg CO2e/kg', 'source' => 'Poore & Nemecek 2018'],
                ['name' => 'Tahu',                    'factor_value' => 3.2,  'unit' => 'kg CO2e/kg', 'source' => 'Poore & Nemecek 2018'],
                ['name' => 'Tempe',                   'factor_value' => 2.0,  'unit' => 'kg CO2e/kg', 'source' => 'Poore & Nemecek 2018'],
                ['name' => 'Kopi',                    'factor_value' => 28.5, 'unit' => 'kg CO2e/kg', 'source' => 'Poore & Nemecek 2018'],
                ['name' => 'Teh',                     'factor_value' => 0.1,  'unit' => 'kg CO2e/kg', 'source' => 'Poore & Nemecek 2018'],
                ['name' => 'Roti',                    'factor_value' => 1.6,  'unit' => 'kg CO2e/kg', 'source' => 'Poore & Nemecek 2018'],
                ['name' => 'Mie Instan',              'factor_value' => 1.5,  'unit' => 'kg CO2e/kg', 'source' => 'Poore & Nemecek 2018'],
            ],

            // ── 5. Sampah (kg CO2e per kg waste, IPCC Waste Model) ──────────
            'sampah' => [
                ['name' => 'Plastik',       'factor_value' => 6.0,  'unit' => 'kg CO2e/kg', 'source' => 'IPCC Waste Model'],
                ['name' => 'Kertas/Karton', 'factor_value' => 1.04, 'unit' => 'kg CO2e/kg', 'source' => 'IPCC Waste Model'],
            ],

            // ── 6. Kendaraan (type in metadata: bbm/ev/public) ─────────────
            'kendaraan' => [
                ['name' => 'Mobil Bensin',        'factor_value' => 2.33,       'unit' => 'kg CO2e/liter',    'source' => 'IPCC 2006 Vol2', 'metadata' => ['type' => 'bbm', 'ef_per' => 'liter']],
                ['name' => 'Mobil Solar',         'factor_value' => 2.68,       'unit' => 'kg CO2e/liter',    'source' => 'IPCC 2006 Vol2', 'metadata' => ['type' => 'bbm', 'ef_per' => 'liter']],
                ['name' => 'Mobil Listrik',       'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh',      'source' => 'ESDM RI',        'metadata' => ['type' => 'ev',  'default_kwh_km' => 0.15]],
                ['name' => 'Motor Bensin',        'factor_value' => 2.33,       'unit' => 'kg CO2e/liter',    'source' => 'IPCC 2006 Vol2', 'metadata' => ['type' => 'bbm', 'ef_per' => 'liter']],
                ['name' => 'Motor Listrik',       'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh',      'source' => 'ESDM RI',        'metadata' => ['type' => 'ev',  'default_kwh_km' => 0.03]],
                ['name' => 'Bus Solar (Publik)',   'factor_value' => 0.104,      'unit' => 'kg CO2e/km/pax',   'source' => 'IEA 2022',       'metadata' => ['type' => 'public']],
                ['name' => 'Bus Listrik (Publik)', 'factor_value' => 0.04,       'unit' => 'kg CO2e/km/pax',   'source' => 'IEA 2022',       'metadata' => ['type' => 'public']],
            ],
        ];

        foreach ($data as $categorySlug => $items) {
            $category = Category::where('slug', $categorySlug)->first();

            if (! $category) {
                continue;
            }

            foreach ($items as $item) {
                EmissionFactor::updateOrCreate(
                    ['name' => $item['name'], 'category_id' => $category->id],
                    [
                        'factor_value' => $item['factor_value'],
                        'unit'         => $item['unit'],
                        'source'       => $item['source'] ?? null,
                        'metadata'     => $item['metadata'] ?? null,
                        'category_id'  => $category->id,
                    ]
                );
            }
        }
    }
}

