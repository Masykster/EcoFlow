<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;

class NudgeService
{
    private array $nudges = [
        'transport' => [
            'Gunakan MRT/KRL untuk hemat hingga 85% emisi karbon dibanding kendaraan pribadi! 🚆',
            'Naik sepeda ke kantor hari ini? Kamu bisa hemat ~2.1 kg CO2e! 🚲',
            'Carpooling dengan 3 orang = emisi transportasi dibagi 4. Ajak teman! 🚗',
        ],
        'food' => [
            'Menu sayur hari ini = 5x lebih rendah emisi dari menu daging sapi. 🥗',
            'Bawa tumbler sendiri hemat plastik + karbon produksi kemasan! ♻️',
            'Pilih makanan lokal = rantai pasokan pendek = emisi lebih rendah. 🌾',
        ],
        'fashion' => [
            'Pakaian secondhand mengurangi emisi produksi hingga 70%! 👕',
            'Cuci baju di suhu dingin hemat energi ~40% per siklus. 🫧',
        ],
        'electricity' => [
            'Cabut charger saat tidak dipakai — phantom load = 10% tagihan listrik! ⚡',
            'AC 1 derajat lebih tinggi = hemat ~6% listrik. Coba atur ke 26°C. ❄️',
        ],
        'default' => [
            'Jejak karbon kamu hari ini bisa dikurangi! Coba pilih transportasi umum. 🌍',
            'Tanam 1 pohon = serap ~22 kg CO2 per tahun. Mulai dari rumah! 🌱',
            'Kurangi 1 perjalanan motor per minggu = hemat ~8.4 kg CO2e/bulan. 🏍️',
        ],
    ];

    /**
     * Return 3 random tips based on user's highest emission category.
     */
    public function getGreenNudges(User $user): array
    {
        $topSlug = Transaction::query()
            ->where('user_id', $user->id)
            ->whereNotNull('co2e')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->selectRaw('categories.slug, SUM(transactions.co2e) as total_co2e')
            ->groupBy('categories.slug')
            ->orderByDesc('total_co2e')
            ->value('slug');

        $pool = $this->nudges[$topSlug] ?? $this->nudges['default'];

        shuffle($pool);

        return array_slice($pool, 0, 3);
    }
}
