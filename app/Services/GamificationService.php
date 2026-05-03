<?php

namespace App\Services;

use App\Models\User;
use App\Models\Transaction;
use App\Models\UserPoint;

class GamificationService
{
    // Points awarded per action
    private const POINTS_LOW_EMISSION_TRANSPORT = 50;
    private const POINTS_VEGETARIAN_MEAL        = 20;
    private const TARGET_DAILY_KG               = 5.0; // kg CO2e green target/day

    private array $badgeRules = [
        'Pejuang MRT'        => ['slug' => 'transport', 'max_co2e' => 0.5],
        'Vegetarian Mingguan' => ['slug' => 'food',      'max_co2e' => 0.3],
        'Eco Warrior'        => ['min_points' => 500],
        'Carbon Fighter'     => ['min_points' => 200],
    ];

    public function awardPoints(User $user, Transaction $transaction): void
    {
        $points = 0;

        if ($transaction->type === 'transport' && $transaction->co2e <= 0.5) {
            $points += self::POINTS_LOW_EMISSION_TRANSPORT;
        }

        if ($transaction->category?->slug === 'food' && $transaction->co2e <= 0.3) {
            $points += self::POINTS_VEGETARIAN_MEAL;
        }

        if ($points <= 0) {
            return;
        }

        $userPoint = UserPoint::firstOrCreate(
            ['user_id' => $user->id],
            ['points' => 0, 'badges' => []]
        );

        $userPoint->increment('points', $points);
        $this->checkBadges($user, $userPoint->fresh());
    }

    public function checkBadges(User $user, ?UserPoint $userPoint = null): void
    {
        $userPoint ??= UserPoint::firstOrCreate(
            ['user_id' => $user->id],
            ['points' => 0, 'badges' => []]
        );

        $earned = $userPoint->badges ?? [];

        foreach ($this->badgeRules as $badge => $rule) {
            if (in_array($badge, $earned)) {
                continue;
            }

            if (isset($rule['min_points']) && $userPoint->points >= $rule['min_points']) {
                $earned[] = $badge;
                continue;
            }

            if (isset($rule['slug'])) {
                $qualifies = $user->transactions()
                    ->whereHas('category', fn ($q) => $q->where('slug', $rule['slug']))
                    ->where('co2e', '<=', $rule['max_co2e'])
                    ->exists();

                if ($qualifies) {
                    $earned[] = $badge;
                }
            }
        }

        $userPoint->update(['badges' => $earned]);
    }

    public function getLeaderboard(int $limit = 10): array
    {
        return UserPoint::with('user:id,name')
            ->orderByDesc('points')
            ->limit($limit)
            ->get()
            ->map(fn ($up) => [
                'name'   => $up->user->name,
                'points' => $up->points,
                'badges' => $up->badges,
            ])
            ->toArray();
    }
}
