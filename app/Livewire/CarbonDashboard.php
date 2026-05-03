<?php

namespace App\Livewire;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CarbonDashboard extends Component
{
    public string $period = 'weekly';

    public float $totalCo2e = 0;
    public float $avgCo2e   = 0;
    public array $byCategory = [];
    public array $nudges     = [];
    public array $recentTransactions = [];

    public function mount(): void
    {
        $this->loadData();
    }

    public function setPeriod(string $period): void
    {
        $this->period = $period;
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
                DB::raw('SUM(transactions.co2e) as total_co2e'),
                DB::raw('COUNT(*) as count')
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

        $this->recentTransactions = Transaction::where('user_id', $user->id)
            ->with('category:id,name,slug')
            ->whereNotNull('co2e')
            ->latest('transacted_at')
            ->limit(5)
            ->get()
            ->toArray();

        // Green nudges from NudgeService
        $this->nudges = app(\App\Services\NudgeService::class)->getGreenNudges($user);
    }

    public function render()
    {
        return view('livewire.carbon-dashboard');
    }
}
