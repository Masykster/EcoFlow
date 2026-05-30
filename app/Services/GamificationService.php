<?php

namespace App\Services;

use App\Models\User;
use App\Models\Transaction;
use App\Models\UserPoint;

class GamificationService
{
    private array $badgeRules = [
        'Pejuang MRT'         => ['slug' => 'kendaraan',   'max_co2e' => 1.0],
        'Vegetarian Mingguan' => ['slug' => 'makanan',     'max_co2e' => 1.0],
        'Penyelamat Energi'   => ['slug' => 'elektronik',  'max_co2e' => 2.0],
        'Pahlawan Sampah'     => ['slug' => 'sampah',      'max_co2e' => 3.0],
        'Penghemat BBM'       => ['slug' => 'bahan_bakar', 'max_co2e' => 5.0],
        'Carbon Fighter'      => ['min_points' => 100],
        'Eco Warrior'         => ['min_points' => 300],
        'Eco Master'          => ['min_points' => 500],
    ];

    public function awardPoints(User $user, Transaction $transaction): void
    {
        $points = 0;
        $categorySlug = $transaction->category?->slug;
        $co2e = (float) $transaction->co2e;

        if ($categorySlug === 'kendaraan' || $categorySlug === 'penerbangan') {
            if ($co2e <= 5.0) {
                $points += 150;
            } else if ($co2e <= 15.0) {
                $points += 80;
            }
        } elseif ($categorySlug === 'makanan') {
            if ($co2e <= 1.0) {
                $points += 100;
            } else if ($co2e <= 3.0) {
                $points += 50;
            }
        } elseif ($categorySlug === 'elektronik') {
            if ($co2e <= 2.0) {
                $points += 100;
            } else if ($co2e <= 5.0) {
                $points += 50;
            }
        } elseif ($categorySlug === 'sampah') {
            if ($co2e <= 3.0) {
                $points += 100;
            }
        } elseif ($categorySlug === 'bahan_bakar') {
            if ($co2e <= 5.0) {
                $points += 100;
            }
        }

        if ($points === 0) {
            $points = 50;
        }

        $userPoint = UserPoint::firstOrCreate(
            ['user_id' => $user->id],
            ['points' => 0, 'badges' => []]
        );

        $userPoint->increment('points', $points);
        $this->checkBadges($user, $userPoint->fresh());
    }

    public function catchUpPoints(User $user): void
    {
        $userPoint = UserPoint::firstOrCreate(
            ['user_id' => $user->id],
            ['points' => 0, 'badges' => []]
        );

        $transactions = $user->transactions()->with('category')->get();
        $totalPoints = 0;

        foreach ($transactions as $transaction) {
            $points = 0;
            $categorySlug = $transaction->category?->slug;
            $co2e = (float) $transaction->co2e;

            if ($categorySlug === 'kendaraan' || $categorySlug === 'penerbangan') {
                if ($co2e <= 5.0) {
                    $points += 150;
                } else if ($co2e <= 15.0) {
                    $points += 80;
                }
            } elseif ($categorySlug === 'makanan') {
                if ($co2e <= 1.0) {
                    $points += 100;
                } else if ($co2e <= 3.0) {
                    $points += 50;
                }
            } elseif ($categorySlug === 'elektronik') {
                if ($co2e <= 2.0) {
                    $points += 100;
                } else if ($co2e <= 5.0) {
                    $points += 50;
                }
            } elseif ($categorySlug === 'sampah') {
                if ($co2e <= 3.0) {
                    $points += 100;
                }
            } elseif ($categorySlug === 'bahan_bakar') {
                if ($co2e <= 5.0) {
                    $points += 100;
                }
            }

            if ($points === 0) {
                $points = 50;
            }
            
            $totalPoints += $points;
        }

        if ($totalPoints > $userPoint->points) {
            $userPoint->points = $totalPoints;
            $userPoint->save();
        }

        $this->checkBadges($user, $userPoint);
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
