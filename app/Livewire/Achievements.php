<?php

namespace App\Livewire;

use App\Models\UserPoint;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Achievements extends Component
{
    public array $earned  = [];
    public int   $points  = 0;
    public array $allBadges = [];

    private array $badgeMeta = [
        'Pejuang MRT'         => ['icon' => 'mrt',        'desc' => 'Gunakan transportasi rendah emisi (CO2 ≤ 1.0 kg per perjalanan)'],
        'Vegetarian Mingguan' => ['icon' => 'vegetarian', 'desc' => 'Pilih makanan rendah karbon (CO2 ≤ 1.0 kg per makan)'],
        'Penyelamat Energi'   => ['icon' => 'energy',     'desc' => 'Gunakan elektronik secara efisien (CO2 ≤ 2.0 kg per log)'],
        'Pahlawan Sampah'     => ['icon' => 'recycle',    'desc' => 'Kelola sampah dan daur ulang (CO2 ≤ 3.0 kg per log)'],
        'Penghemat BBM'       => ['icon' => 'fuel',       'desc' => 'Kurangi konsumsi bahan bakar fosil (CO2 ≤ 5.0 kg per log)'],
        'Carbon Fighter'      => ['icon' => 'carbon',     'desc' => 'Kumpulkan 100 poin EcoStep'],
        'Eco Warrior'         => ['icon' => 'warrior',    'desc' => 'Kumpulkan 300 poin EcoStep'],
        'Eco Master'          => ['icon' => 'master',     'desc' => 'Kumpulkan 500 poin EcoStep'],
    ];

    public function mount(): void
    {
        $user      = Auth::user();
        
        // Trigger catch up to calculate points and unlock badges for historical transactions
        app(\App\Services\GamificationService::class)->catchUpPoints($user);

        $userPoint = UserPoint::where('user_id', $user->id)->first();

        $this->points = $userPoint?->points ?? 0;
        $this->earned = $userPoint?->badges ?? [];

        $this->allBadges = collect($this->badgeMeta)->map(function ($meta, $name) {
            return array_merge($meta, [
                'name'   => $name,
                'earned' => in_array($name, $this->earned),
            ]);
        })->values()->toArray();
    }

    public function render()
    {
        return view('livewire.achievements');
    }
}
