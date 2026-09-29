<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\EmissionFactor;
use App\Models\Transaction;
use App\Services\GamificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Url;

class CarbonCalculator extends Component
{
    #[Url]
    public string $activeTab = 'bahan_bakar';

    // ── Tab: Bahan Bakar ─────────────────────────────────────────────────────
    public ?int   $bb_ef_id   = null; // selected EmissionFactor id
    public float  $bb_liter   = 0;

    // ── Tab: Elektronik ──────────────────────────────────────────────────────
    public ?int   $el_ef_id   = null;
    public float  $el_unit    = 1;
    public float  $el_jam     = 0;

    // ── Tab: Penerbangan ─────────────────────────────────────────────────────
    public ?int   $fl_ef_id   = null; // Ekonomi or Bisnis
    public int    $fl_freq    = 1;
    public float  $fl_km      = 0;
    public int    $fl_arah    = 1; // 1=one way, 2=return

    // ── Tab: Makanan ─────────────────────────────────────────────────────────
    public ?int   $mk_ef_id   = null;
    public float  $mk_gram    = 0;

    // ── Tab: Sampah ───────────────────────────────────────────────────────────
    public ?int   $sp_ef_id   = null;
    public float  $sp_kg      = 0;

    // ── Tab: Kendaraan ───────────────────────────────────────────────────────
    public ?int   $kd_ef_id   = null;
    public float  $kd_km      = 0;
    public float  $kd_eff     = 12; // km/L (bbm) or kWh/km (ev)
    public int    $kd_pax     = 1;

    // ── Shared ───────────────────────────────────────────────────────────────
    public string $description = '';
    public float  $previewCo2e = 0;
    public bool   $saved       = false;
    public string $errorMsg    = '';
    public bool   $isGuestMode = false;

    // Loaded from DB
    public array $categories    = [];
    public array $efByCategory  = [];  // ['bahan_bakar' => [...EF rows...], ...]

    public function mount(): void
    {
        // Guest mode (landing page) fully works without a database:
        // emission factors come from a static snapshot mirroring the seeders.
        if ($this->isGuestMode) {
            $this->loadStaticData();
        } else {
            $this->loadData();
        }
    }

    private function loadData(): void
    {
        $this->categories = Category::orderBy('name')->get()->keyBy('slug')->toArray();

        $slugs = ['bahan_bakar', 'elektronik', 'penerbangan', 'makanan', 'sampah', 'kendaraan'];

        foreach ($slugs as $slug) {
            $cat = Category::where('slug', $slug)->first();
            if ($cat) {
                $this->efByCategory[$slug] = EmissionFactor::where('category_id', $cat->id)
                    ->get(['id', 'name', 'factor_value', 'unit', 'metadata'])
                    ->toArray();
            }
        }

        // Set defaults
        $this->bb_ef_id = $this->efByCategory['bahan_bakar'][5]['id'] ?? null; // Pertamax
        $this->fl_ef_id = $this->efByCategory['penerbangan'][0]['id'] ?? null; // Ekonomi
        $this->mk_ef_id = $this->efByCategory['makanan'][0]['id'] ?? null;     // Telur
        $this->sp_ef_id = $this->efByCategory['sampah'][0]['id'] ?? null;      // Plastik
        $this->el_ef_id = $this->efByCategory['elektronik'][0]['id'] ?? null;
        $this->kd_ef_id = $this->efByCategory['kendaraan'][3]['id'] ?? null;   // Motor Bensin
    }

    /**
     * Static snapshot of CategorySeeder + EmissionFactorSeeder.
     * Used in guest mode so the landing page renders and calculates
     * with zero database queries. Negative ids can never collide
     * with auto-increment DB ids.
     */
    private function loadStaticData(): void
    {
        $dataset = self::staticDataset();

        $this->categories = $dataset['categories'];

        $nextId = -1;
        foreach ($dataset['factors'] as $slug => $items) {
            $rows = [];
            foreach ($items as $item) {
                $rows[] = [
                    'id'           => $nextId--,
                    'name'         => $item['name'],
                    'factor_value' => $item['factor_value'],
                    'unit'         => $item['unit'],
                    'metadata'     => $item['metadata'] ?? null,
                ];
            }
            $this->efByCategory[$slug] = $rows;
        }

        // Set defaults (same indices as loadData)
        $this->bb_ef_id = $this->efByCategory['bahan_bakar'][5]['id'] ?? null; // Pertamax
        $this->fl_ef_id = $this->efByCategory['penerbangan'][0]['id'] ?? null; // Ekonomi
        $this->mk_ef_id = $this->efByCategory['makanan'][0]['id'] ?? null;     // Telur
        $this->sp_ef_id = $this->efByCategory['sampah'][0]['id'] ?? null;      // Plastik
        $this->el_ef_id = $this->efByCategory['elektronik'][0]['id'] ?? null;
        $this->kd_ef_id = $this->efByCategory['kendaraan'][3]['id'] ?? null;   // Motor Bensin
    }

    private static function staticDataset(): array
    {
        $efListrik = 0.87; // kg CO2e/kWh PLN (ESDM RI)

        return [
            'categories' => [
                'bahan_bakar' => ['slug' => 'bahan_bakar', 'name' => 'Bahan Bakar', 'emission_factor' => 2.33, 'unit' => 'liter', 'description' => 'Minyak tanah, LPG, solar, bensin rumah tangga'],
                'elektronik'  => ['slug' => 'elektronik', 'name' => 'Elektronik', 'emission_factor' => 0.87, 'unit' => 'kwh', 'description' => 'Penggunaan perangkat elektronik rumah tangga'],
                'penerbangan' => ['slug' => 'penerbangan', 'name' => 'Penerbangan', 'emission_factor' => 0.15, 'unit' => 'km', 'description' => 'Perjalanan udara domestik/internasional'],
                'makanan'     => ['slug' => 'makanan', 'name' => 'Makanan', 'emission_factor' => 4.8, 'unit' => 'kg_food', 'description' => 'Konsumsi bahan makanan sehari-hari'],
                'sampah'      => ['slug' => 'sampah', 'name' => 'Sampah', 'emission_factor' => 6.0, 'unit' => 'kg', 'description' => 'Sampah plastik, kertas/karton'],
                'kendaraan'   => ['slug' => 'kendaraan', 'name' => 'Kendaraan', 'emission_factor' => 2.33, 'unit' => 'km', 'description' => 'Kendaraan pribadi berbahan bakar atau listrik'],
            ],
            'factors' => [
                'bahan_bakar' => [
                    ['name' => 'Minyak Tanah', 'factor_value' => 2.52, 'unit' => 'kg CO2e/liter'],
                    ['name' => 'Minyak Residu', 'factor_value' => 3.11, 'unit' => 'kg CO2e/liter'],
                    ['name' => 'LPG', 'factor_value' => 1.53, 'unit' => 'kg CO2e/liter'],
                    ['name' => 'Diesel / Solar', 'factor_value' => 2.68, 'unit' => 'kg CO2e/liter'],
                    ['name' => 'Biosolar (B35)', 'factor_value' => 1.74, 'unit' => 'kg CO2e/liter'],
                    ['name' => 'Pertamax', 'factor_value' => 2.33, 'unit' => 'kg CO2e/liter'],
                    ['name' => 'Pertalite', 'factor_value' => 2.33, 'unit' => 'kg CO2e/liter'],
                    ['name' => 'Pertamax Turbo', 'factor_value' => 2.33, 'unit' => 'kg CO2e/liter'],
                    ['name' => 'Pertamax Green', 'factor_value' => 2.20, 'unit' => 'kg CO2e/liter'],
                ],
                'elektronik' => [
                    ['name' => 'Setrika', 'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'metadata' => ['watt' => 350]],
                    ['name' => 'Mesin Cuci', 'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'metadata' => ['watt' => 300]],
                    ['name' => 'Dispenser', 'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'metadata' => ['watt' => 350]],
                    ['name' => 'Kipas Angin', 'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'metadata' => ['watt' => 50]],
                    ['name' => 'Komputer (PC)', 'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'metadata' => ['watt' => 200]],
                    ['name' => 'Laptop', 'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'metadata' => ['watt' => 50]],
                    ['name' => 'Kulkas', 'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'metadata' => ['watt' => 100]],
                    ['name' => 'Printer', 'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'metadata' => ['watt' => 30]],
                    ['name' => 'Televisi', 'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'metadata' => ['watt' => 100]],
                    ['name' => 'Rice Cooker', 'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'metadata' => ['watt' => 350]],
                    ['name' => 'Kompor Listrik', 'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'metadata' => ['watt' => 1000]],
                    ['name' => 'Blender', 'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'metadata' => ['watt' => 300]],
                    ['name' => 'Oven Listrik', 'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'metadata' => ['watt' => 1000]],
                    ['name' => 'Microwave', 'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'metadata' => ['watt' => 800]],
                    ['name' => 'Vacuum Cleaner', 'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'metadata' => ['watt' => 600]],
                    ['name' => 'Water Heater', 'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'metadata' => ['watt' => 800]],
                ],
                'penerbangan' => [
                    ['name' => 'Ekonomi', 'factor_value' => 0.15, 'unit' => 'kg CO2e/km'],
                    ['name' => 'Bisnis/First Class', 'factor_value' => 0.45, 'unit' => 'kg CO2e/km'],
                ],
                'makanan' => [
                    ['name' => 'Telur', 'factor_value' => 4.8, 'unit' => 'kg CO2e/kg'],
                    ['name' => 'Susu', 'factor_value' => 3.2, 'unit' => 'kg CO2e/kg'],
                    ['name' => 'Ikan', 'factor_value' => 5.1, 'unit' => 'kg CO2e/kg'],
                    ['name' => 'Beras', 'factor_value' => 4.5, 'unit' => 'kg CO2e/kg'],
                    ['name' => 'Seafood (Udang/Kerang)', 'factor_value' => 26.9, 'unit' => 'kg CO2e/kg'],
                    ['name' => 'Unggas (Ayam/Bebek)', 'factor_value' => 9.9, 'unit' => 'kg CO2e/kg'],
                    ['name' => 'Daging Domba', 'factor_value' => 39.7, 'unit' => 'kg CO2e/kg'],
                    ['name' => 'Daging Sapi', 'factor_value' => 99.5, 'unit' => 'kg CO2e/kg'],
                    ['name' => 'Daging Babi', 'factor_value' => 12.3, 'unit' => 'kg CO2e/kg'],
                    ['name' => 'Keju', 'factor_value' => 23.9, 'unit' => 'kg CO2e/kg'],
                    ['name' => 'Tahu', 'factor_value' => 3.2, 'unit' => 'kg CO2e/kg'],
                    ['name' => 'Tempe', 'factor_value' => 2.0, 'unit' => 'kg CO2e/kg'],
                    ['name' => 'Kopi', 'factor_value' => 28.5, 'unit' => 'kg CO2e/kg'],
                    ['name' => 'Teh', 'factor_value' => 0.1, 'unit' => 'kg CO2e/kg'],
                    ['name' => 'Roti', 'factor_value' => 1.6, 'unit' => 'kg CO2e/kg'],
                    ['name' => 'Mie Instan', 'factor_value' => 1.5, 'unit' => 'kg CO2e/kg'],
                ],
                'sampah' => [
                    ['name' => 'Plastik', 'factor_value' => 6.0, 'unit' => 'kg CO2e/kg'],
                    ['name' => 'Kertas/Karton', 'factor_value' => 1.04, 'unit' => 'kg CO2e/kg'],
                ],
                'kendaraan' => [
                    ['name' => 'Mobil Bensin', 'factor_value' => 2.33, 'unit' => 'kg CO2e/liter', 'metadata' => ['type' => 'bbm']],
                    ['name' => 'Mobil Solar', 'factor_value' => 2.68, 'unit' => 'kg CO2e/liter', 'metadata' => ['type' => 'bbm']],
                    ['name' => 'Mobil Listrik', 'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'metadata' => ['type' => 'ev', 'default_kwh_km' => 0.15]],
                    ['name' => 'Motor Bensin', 'factor_value' => 2.33, 'unit' => 'kg CO2e/liter', 'metadata' => ['type' => 'bbm']],
                    ['name' => 'Motor Listrik', 'factor_value' => $efListrik, 'unit' => 'kg CO2e/kWh', 'metadata' => ['type' => 'ev', 'default_kwh_km' => 0.03]],
                    ['name' => 'Bus Solar (Publik)', 'factor_value' => 0.104, 'unit' => 'kg CO2e/km/pax', 'metadata' => ['type' => 'public']],
                    ['name' => 'Bus Listrik (Publik)', 'factor_value' => 0.04, 'unit' => 'kg CO2e/km/pax', 'metadata' => ['type' => 'public']],
                ],
            ],
        ];
    }

    // ── Reactive recalculate on any property change ──────────────────────────

    public function updated($property): void
    {
        $this->recalculate();
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->recalculate();
    }

    private function recalculate(): void
    {
        $this->previewCo2e = 0;
        $this->errorMsg    = '';

        try {
            $this->previewCo2e = match ($this->activeTab) {
                'bahan_bakar' => $this->calcBahanBakar(),
                'elektronik'  => $this->calcElektronik(),
                'penerbangan' => $this->calcPenerbangan(),
                'makanan'     => $this->calcMakanan(),
                'sampah'      => $this->calcSampah(),
                'kendaraan'   => $this->calcKendaraan(),
                default       => 0,
            };
        } catch (\Throwable $e) {
            $this->previewCo2e = 0;
        }

        $this->previewCo2e = round(max(0, $this->previewCo2e), 4);
    }

    // ── Formula per tab (EF dari DB) ─────────────────────────────────────────

    // CO2e = liter × EF (kg CO2e/liter)
    private function calcBahanBakar(): float
    {
        $ef = $this->getEF($this->bb_ef_id);
        return $this->bb_liter * $ef;
    }

    // CO2e = unit × jam × (watt/1000) × EF_PLN  (single usage session)
    private function calcElektronik(): float
    {
        $ef   = $this->getEF($this->el_ef_id);
        $watt = $this->getEFMeta($this->el_ef_id, 'watt', 100);
        $kwh  = ($this->el_unit * $this->el_jam * $watt) / 1000;
        return $kwh * $ef;
    }

    // CO2e = freq × km × EF_kelas × arah (1 or 2)
    private function calcPenerbangan(): float
    {
        $ef = $this->getEF($this->fl_ef_id);
        return $this->fl_freq * $this->fl_km * $ef * $this->fl_arah;
    }

    // CO2e = (gram / 1000) × EF_makanan
    private function calcMakanan(): float
    {
        $ef = $this->getEF($this->mk_ef_id);
        return ($this->mk_gram / 1000) * $ef;
    }

    // CO2e = kg × EF_sampah
    private function calcSampah(): float
    {
        $ef = $this->getEF($this->sp_ef_id);
        return $this->sp_kg * $ef;
    }

    // CO2e depends on vehicle type in metadata
    private function calcKendaraan(): float
    {
        $ef   = $this->getEF($this->kd_ef_id);
        $meta = $this->getEFMetaFull($this->kd_ef_id);
        $type = $meta['type'] ?? 'bbm';
        $km   = $this->kd_km;
        $pax  = max(1, $this->kd_pax);

        if ($type === 'bbm') {
            // CO2e = (km / km_per_liter) × EF / pax
            $kml = max(0.1, $this->kd_eff);
            return (($km / $kml) * $ef) / $pax;
        } elseif ($type === 'ev') {
            // CO2e = km × kWh/km × EF_PLN / pax
            return ($km * $this->kd_eff * $ef) / $pax;
        } else {
            // public: CO2e = km × EF_per_pax
            return $km * $ef;
        }
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * In-memory lookup over the already loaded $efByCategory rows
     * (DB rows in auth mode, static snapshot in guest mode).
     * Avoids a DB query on every keystroke recalculation.
     */
    private function lookupEF(?int $id): ?array
    {
        if (! $id) return null;
        foreach ($this->efByCategory as $rows) {
            foreach ($rows as $row) {
                if (($row['id'] ?? null) === $id) return $row;
            }
        }
        return null;
    }

    private function getEF(?int $id): float
    {
        return (float) ($this->lookupEF($id)['factor_value'] ?? 0);
    }

    private function getEFMeta(?int $id, string $key, $default = null): mixed
    {
        $meta = $this->lookupEF($id)['metadata'] ?? null;
        if (! is_array($meta)) return $default;
        return $meta[$key] ?? $default;
    }

    private function getEFMetaFull(?int $id): array
    {
        $meta = $this->lookupEF($id)['metadata'] ?? [];
        return is_array($meta) ? $meta : [];
    }

    // ── Save transaction ──────────────────────────────────────────────────────

    public function saveTransaction(): void
    {
        $this->errorMsg = '';

        if ($this->isGuestMode) {
            $this->errorMsg = 'Masuk untuk menyimpan riwayat';
            return;
        }

        if ($this->previewCo2e <= 0) {
            $this->errorMsg = 'Masukkan data terlebih dahulu';
            return;
        }

        $user     = Auth::user();
        $category = Category::where('slug', $this->activeTab)->first();

        $desc = $this->description ?: $this->buildAutoDesc();

        $transaction = Transaction::create([
            'user_id'       => $user->id,
            'merchant_name' => $desc,
            'amount'        => 0,
            'category_id'   => $category?->id,
            'type'          => 'spending',
            'distance_km'   => $this->activeTab === 'kendaraan' ? $this->kd_km : null,
            'co2e'          => $this->previewCo2e,
            'transacted_at' => now(),
        ]);

        app(GamificationService::class)->awardPoints($user, $transaction);

        $this->saved = true;
        $this->reset(['description', 'bb_liter', 'el_jam', 'fl_freq', 'fl_km', 'mk_gram', 'sp_kg', 'kd_km']);
        $this->previewCo2e = 0;

        $this->dispatch('transaction-saved');
    }

    private function buildAutoDesc(): string
    {
        $row = $this->lookupEF(match ($this->activeTab) {
            'bahan_bakar' => $this->bb_ef_id,
            'elektronik'  => $this->el_ef_id,
            'penerbangan' => $this->fl_ef_id,
            'makanan'     => $this->mk_ef_id,
            'sampah'      => $this->sp_ef_id,
            'kendaraan'   => $this->kd_ef_id,
            default       => null,
        });

        $tabLabel = [
            'bahan_bakar' => 'Bahan Bakar',
            'elektronik'  => 'Elektronik',
            'penerbangan' => 'Penerbangan',
            'makanan'     => 'Makanan',
            'sampah'      => 'Sampah',
            'kendaraan'   => 'Kendaraan',
        ][$this->activeTab] ?? $this->activeTab;

        return $row ? "{$tabLabel} - {$row['name']}" : $tabLabel;
    }

    public function render()
    {
        return view('livewire.carbon-calculator');
    }
}

