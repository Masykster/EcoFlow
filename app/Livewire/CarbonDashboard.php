<?php

namespace App\Livewire;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Carbon\Carbon;

class CarbonDashboard extends Component
{
    public string $period = 'weekly';

    public float $totalCo2e = 0;
    public float $avgCo2e   = 0;
    public array $byCategory = [];
    public array $recentTransactions = [];
    
    // New variables
    public array $dailyEmissions = [];
    public array $dynamicNudge = [];
    public float $targetEmission = 2.0;

    // ── Feature 1: Carbon Budgeting ──────────────────────────────────────────
    public float $monthlyLimit = 100;
    public float $monthlyUsed = 0;
    public float $budgetPercent = 0;
    public string $budgetStatus = 'safe'; // safe, warning, danger
    public bool $showBudgetModal = false;
    public float $newBudgetLimit = 100;

    // ── Feature 2: AI Forecaster ─────────────────────────────────────────────
    public float $forecastEndMonth = 0;
    public bool $forecastOverBudget = false;
    public array $smartRecommendation = [];

    // ── Feature 4: Virtual Eco-Home ──────────────────────────────────────────
    public string $ecoHomeState = 'thriving'; // thriving, healthy, stressed, struggling, critical
    public int $ecoHomeLevel = 100; // 0-100

    public function mount(): void
    {
        $user = Auth::user();
        $this->monthlyLimit = $user->monthly_carbon_limit ?? 100;
        $this->newBudgetLimit = $this->monthlyLimit;
        $this->loadData();
    }

    public function setPeriod(string $period): void
    {
        $this->period = $period;
        $this->loadData();
    }

    public function openBudgetModal(): void
    {
        $this->showBudgetModal = true;
    }

    public function closeBudgetModal(): void
    {
        $this->showBudgetModal = false;
    }

    public function saveBudgetLimit(): void
    {
        $this->newBudgetLimit = max(1, $this->newBudgetLimit);
        $user = Auth::user();
        $user->monthly_carbon_limit = $this->newBudgetLimit;
        $user->save();

        $this->monthlyLimit = $this->newBudgetLimit;
        $this->showBudgetModal = false;
        $this->loadData();
    }

    private function loadData(): void
    {
        $user = Auth::user();

        [$from, $to] = match ($this->period) {
            'daily'   => [now()->startOfDay(), now()],
            'monthly' => [now()->subMonth(), now()],
            default   => [now()->subWeek(), now()],
        };

        $rows = Transaction::where('user_id', $user->id)
            ->whereBetween('transacted_at', [$from, $to])
            ->whereNotNull('co2e')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->select(
                'categories.name as category',
                'categories.slug as slug',
                DB::raw('SUM(transactions.co2e) as total_co2e')
            )
            ->groupBy('categories.name', 'categories.slug')
            ->orderByDesc('total_co2e')
            ->get();

        $this->totalCo2e    = round((float) $rows->sum('total_co2e'), 3);
        $this->byCategory   = $rows->toArray();

        $this->avgCo2e = round(
            (float) Transaction::whereNotNull('co2e')->avg('co2e') ?? 0,
            3
        );

        $categoryAverages = Transaction::whereNotNull('co2e')
            ->select('category_id', DB::raw('AVG(co2e) as avg_co2e'))
            ->groupBy('category_id')
            ->pluck('avg_co2e', 'category_id');

        $this->recentTransactions = Transaction::where('user_id', $user->id)
            ->with('category:id,name,slug')
            ->whereNotNull('co2e')
            ->latest('transacted_at')
            ->limit(5)
            ->get()
            ->map(function ($trx) use ($categoryAverages) {
                $arr = $trx->toArray();
                $arr['cat_avg'] = round($categoryAverages[$trx->category_id] ?? 0, 2);
                return $arr;
            })
            ->toArray();

        // Generate Daily Emissions for 30 Days Trend
        $thirtyDaysAgo = now()->subDays(30)->startOfDay();
        $dailyData = Transaction::where('user_id', $user->id)
            ->where('transacted_at', '>=', $thirtyDaysAgo)
            ->select(
                DB::raw('DATE(transacted_at) as date'),
                DB::raw('SUM(co2e) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $this->dailyEmissions = [];
        for ($i = 30; $i >= 0; $i--) {
            $dateStr = now()->subDays($i)->format('Y-m-d');
            $this->dailyEmissions[] = [
                'date' => Carbon::parse($dateStr)->format('d M'),
                'total' => round($dailyData->get($dateStr)?->total ?? 0, 2)
            ];
        }

        // Dynamic Nudge based on highest category
        $this->dynamicNudge = $this->generateNudge();

        // ── Load budget data ─────────────────────────────────────────────────
        $this->loadBudgetData($user);

        // ── Load forecast ────────────────────────────────────────────────────
        $this->loadForecast($user);

        // ── Load smart recommendation ────────────────────────────────────────
        $this->loadSmartRecommendation();

        // ── Compute eco-home state ───────────────────────────────────────────
        $this->computeEcoHome();
    }

    // ── Feature 1: Carbon Budgeting ──────────────────────────────────────────

    private function loadBudgetData($user): void
    {
        $startOfMonth = now()->startOfMonth();
        $this->monthlyUsed = round(
            (float) Transaction::where('user_id', $user->id)
                ->where('transacted_at', '>=', $startOfMonth)
                ->whereNotNull('co2e')
                ->sum('co2e'),
            2
        );

        $this->budgetPercent = $this->monthlyLimit > 0
            ? round(($this->monthlyUsed / $this->monthlyLimit) * 100, 1)
            : 0;

        $this->budgetStatus = match (true) {
            $this->budgetPercent >= 100 => 'danger',
            $this->budgetPercent >= 75  => 'warning',
            default                     => 'safe',
        };
    }

    // ── Feature 2: AI Forecaster (Linear Extrapolation) ──────────────────────

    private function loadForecast($user): void
    {
        $dayOfMonth = now()->day;
        $daysInMonth = now()->daysInMonth;

        if ($dayOfMonth >= 3 && $this->monthlyUsed > 0) {
            // Linear extrapolation: (used / days_elapsed) * total_days
            $dailyRate = $this->monthlyUsed / $dayOfMonth;
            $this->forecastEndMonth = round($dailyRate * $daysInMonth, 2);
            $this->forecastOverBudget = $this->forecastEndMonth > $this->monthlyLimit;
        } else {
            $this->forecastEndMonth = 0;
            $this->forecastOverBudget = false;
        }
    }

    private function loadSmartRecommendation(): void
    {
        if (empty($this->byCategory)) {
            $this->smartRecommendation = [];
            return;
        }

        $highest = $this->byCategory[0];
        $slug = $highest['slug'];
        $co2e = $highest['total_co2e'];

        // Rule-based: find the biggest category and suggest one actionable swap
        $recommendations = [
            'bahan_bakar' => [
                'icon' => 'fuel',
                'action' => 'Ganti 2 trip mobil dengan transportasi umum minggu depan',
                'saving' => round($co2e * 0.15, 2),
                'detail' => 'Rata-rata trip mobil pribadi 15 km menghasilkan ~2.5 kg CO₂e. KRL/MRT hanya ~0.3 kg.',
            ],
            'elektronik' => [
                'icon' => 'bolt',
                'action' => 'Kurangi pemakaian AC 2 jam per hari',
                'saving' => round($co2e * 0.20, 2),
                'detail' => 'AC 1 PK selama 2 jam menghasilkan ~1.5 kg CO₂e. Gunakan fan saat suhu <30°C.',
            ],
            'penerbangan' => [
                'icon' => 'plane',
                'action' => 'Pilih kelas ekonomi untuk penerbangan berikutnya',
                'saving' => round($co2e * 0.40, 2),
                'detail' => 'Kelas bisnis menghasilkan emisi 3x lipat dari ekonomi per penumpang.',
            ],
            'makanan' => [
                'icon' => 'utensils',
                'action' => 'Ganti 2 porsi daging sapi dengan ayam minggu depan',
                'saving' => round($co2e * 0.25, 2),
                'detail' => 'Daging sapi: ~27 kg CO₂e/kg. Ayam: ~6.9 kg CO₂e/kg. Penghematan signifikan.',
            ],
            'sampah' => [
                'icon' => 'recycle',
                'action' => 'Pisahkan sampah organik dan plastik untuk daur ulang',
                'saving' => round($co2e * 0.18, 2),
                'detail' => 'Sampah organik yang terkompos menghasilkan 60% lebih sedikit metana.',
            ],
            'kendaraan' => [
                'icon' => 'car',
                'action' => 'Carpooling dengan rekan kerja 3 hari seminggu',
                'saving' => round($co2e * 0.30, 2),
                'detail' => 'Carpooling 2 orang memotong emisi per-orang sebesar 50%.',
            ],
        ];

        $this->smartRecommendation = $recommendations[$slug] ?? [
            'icon' => 'lightbulb',
            'action' => "Kurangi aktivitas {$highest['category']} yang paling intensif",
            'saving' => round($co2e * 0.10, 2),
            'detail' => "Kategori ini menyumbang emisi terbesar kamu bulan ini.",
        ];
    }

    // ── Feature 4: Virtual Eco-Home ──────────────────────────────────────────

    private function computeEcoHome(): void
    {
        // Scale 0-100 based on budget usage (inverted: low usage = high score)
        if ($this->monthlyLimit <= 0) {
            $this->ecoHomeLevel = 50;
        } else {
            $ratio = $this->monthlyUsed / $this->monthlyLimit;
            // 0% used = 100 level, 100% used = 20 level, 150%+ = 0
            $this->ecoHomeLevel = max(0, min(100, (int) round(100 - ($ratio * 80))));
        }

        $this->ecoHomeState = match (true) {
            $this->ecoHomeLevel >= 80 => 'thriving',
            $this->ecoHomeLevel >= 60 => 'healthy',
            $this->ecoHomeLevel >= 40 => 'stressed',
            $this->ecoHomeLevel >= 20 => 'struggling',
            default                    => 'critical',
        };
    }

    private function generateNudge(): array
    {
        if (empty($this->byCategory)) {
            return [
                'title' => 'Belum ada data emisi',
                'message' => 'Mulai catat aktivitasmu di kalkulator untuk melihat tips personalisasi.',
                'cta' => 'Mulai Catat',
                'link' => route('calculator')
            ];
        }

        $highest = $this->byCategory[0];
        $slug = $highest['slug'];
        $pct = round($highest['total_co2e'] / $this->totalCo2e * 100, 1);

        $nudges = [
            'bahan_bakar' => [
                'title' => "Tips Edukasi: Bahan Bakar Mendominasi ({$pct}%).",
                'message' => 'Hemat emisi transportasimu hari ini dengan mencoba transportasi publik seperti KRL/MRT.',
                'cta' => 'Pelajari Tips BBM',
                'link' => '#'
            ],
            'elektronik' => [
                'title' => "Tips Edukasi: Elektronik Tinggi ({$pct}%).",
                'message' => 'Matikan perangkat saat tidak digunakan. Hemat energi, hemat biaya.',
                'cta' => 'Tips Hemat Listrik',
                'link' => '#'
            ],
            'kendaraan' => [
                'title' => "Tips Edukasi: Emisi Kendaraan ({$pct}%).",
                'message' => 'Pertimbangkan carpooling atau menggunakan kendaraan umum untuk rutinitas harian.',
                'cta' => 'Alternatif Transport',
                'link' => '#'
            ]
        ];

        return $nudges[$slug] ?? [
            'title' => "Perhatian pada kategori {$highest['category']}.",
            'message' => "Kategori ini menyumbang {$pct}% dari emisimu bulan ini.",
            'cta' => 'Lihat Detail',
            'link' => route('history')
        ];
    }

    public function render()
    {
        return view('livewire.carbon-dashboard');
    }
}
