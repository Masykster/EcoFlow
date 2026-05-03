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
        'Pejuang MRT'         => ['icon' => '🚆', 'desc' => 'Gunakan transportasi rendah emisi (CO2 ≤ 0.5 kg per perjalanan)'],
        'Vegetarian Mingguan' => ['icon' => '🥗', 'desc' => 'Pilih makanan rendah karbon (CO2 ≤ 0.3 kg per makan)'],
        'Carbon Fighter'      => ['icon' => '🌱', 'desc' => 'Kumpulkan 200 poin EcoStep'],
        'Eco Warrior'         => ['icon' => '🌍', 'desc' => 'Kumpulkan 500 poin EcoStep'],
    ];

    public function mount(): void
    {
        $user      = Auth::user();
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
