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
        $this->loadData();
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

    private function getEF(?int $id): float
    {
        if (! $id) return 0;
        $ef = EmissionFactor::find($id);
        return $ef?->factor_value ?? 0;
    }

    private function getEFMeta(?int $id, string $key, $default = null): mixed
    {
        if (! $id) return $default;
        $ef = EmissionFactor::find($id);
        return $ef?->metadata[$key] ?? $default;
    }

    private function getEFMetaFull(?int $id): array
    {
        if (! $id) return [];
        $ef = EmissionFactor::find($id);
        return $ef?->metadata ?? [];
    }

    // ── Save transaction ──────────────────────────────────────────────────────

    public function saveTransaction(): void
    {
        $this->errorMsg = '';

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
        $ef = EmissionFactor::find(match ($this->activeTab) {
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

        return $ef ? "{$tabLabel} - {$ef->name}" : $tabLabel;
    }

    public function render()
    {
        return view('livewire.carbon-calculator');
    }
}

