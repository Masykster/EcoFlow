<div class="p-6 space-y-6">

    {{-- Header + Period Switcher --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--eco-primary)">Dashboard Karbon</h1>
            <p class="text-sm" style="color: var(--eco-text-secondary)">Pantau jejak karbon kamu secara real-time</p>
        </div>
        <div class="flex gap-2">
            @foreach(['daily' => 'Hari Ini', 'weekly' => 'Minggu Ini', 'monthly' => 'Bulan Ini'] as $key => $label)
                <button
                    wire:click="setPeriod('{{ $key }}')"
                    class="eco-btn text-sm px-4 py-2 {{ $period === $key ? 'eco-btn-primary' : 'eco-btn-secondary' }}"
                >{{ $label }}</button>
            @endforeach
        </div>
    </div>

    {{-- Bento Grid --}}
    <div class="bento-grid">

        {{-- Total CO2e (large card) --}}
        <div class="bento-card bento-col-8" style="background: linear-gradient(135deg, var(--eco-primary) 0%, #2D5F50 100%);">
            <p class="eco-number-label" style="color: rgba(163,217,165,0.8)">Total Emisi Karbon</p>
            <div class="mt-3 flex items-end gap-3">
                <span class="eco-number" style="color: #fff; font-size: 3rem;" wire:loading.class="opacity-50">
                    {{ number_format($totalCo2e, 2) }}
                </span>
                <span class="text-lg font-medium mb-1" style="color: rgba(255,255,255,0.7)">kg CO₂e</span>
            </div>
            <div class="mt-4 flex items-center gap-2">
                @if($avgCo2e > 0)
                    @php
                        $diff = $totalCo2e - $avgCo2e;
                        $pct  = round(abs($diff / $avgCo2e * 100), 1);
                    @endphp
                    <span class="eco-badge {{ $diff <= 0 ? 'eco-badge--success' : 'eco-badge--danger' }}"
                          style="{{ $diff <= 0 ? 'background: rgba(16,185,129,0.25); color: #A3D9A5;' : 'background: rgba(239,68,68,0.25); color: #FCA5A5;' }}">
                        {{ $diff <= 0 ? '↓' : '↑' }} {{ $pct }}% vs rata-rata
                    </span>
                @endif
                <span style="color: rgba(255,255,255,0.5); font-size: 0.75rem;">Rata-rata semua user: {{ number_format($avgCo2e, 2) }} kg</span>
            </div>
        </div>

        {{-- Green Nudges --}}
        <div class="bento-card bento-col-4">
            <p class="eco-number-label" style="color: var(--eco-text-secondary)">💡 Green Nudge</p>
            <div class="mt-3 space-y-3">
                @forelse($nudges as $nudge)
                    <div class="eco-nudge">{{ $nudge }}</div>
                @empty
                    <p class="text-sm" style="color: var(--eco-text-secondary)">Tambah transaksi untuk mendapatkan tips personal.</p>
                @endforelse
            </div>
        </div>

        {{-- By Category --}}
        <div class="bento-card bento-col-6">
            <p class="eco-number-label" style="color: var(--eco-text-secondary)">Emisi per Kategori</p>
            <div class="mt-4 space-y-3">
                @forelse($byCategory as $cat)
                    @php
                        $pct = $totalCo2e > 0 ? round($cat['total_co2e'] / $totalCo2e * 100, 1) : 0;
                        $slug = $cat['slug'];
                        $color = "var(--eco-cat-{$slug}, var(--eco-primary))";
                    @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span style="color: var(--eco-text-primary); font-weight: 500;">{{ $cat['category'] }}</span>
                            <span style="color: var(--eco-text-secondary);">{{ number_format($cat['total_co2e'], 2) }} kg ({{ $pct }}%)</span>
                        </div>
                        <div style="height: 6px; background: #F0F0F0; border-radius: 99px; overflow: hidden;">
                            <div style="height: 100%; width: {{ $pct }}%; background: {{ $color }}; border-radius: 99px; transition: width 600ms ease;"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm" style="color: var(--eco-text-secondary)">Belum ada data untuk periode ini.</p>
                @endforelse
            </div>
        </div>

        {{-- Recent Transactions --}}
        <div class="bento-card bento-col-6">
            <div class="flex items-center justify-between mb-4">
                <p class="eco-number-label" style="color: var(--eco-text-secondary)">Transaksi Terbaru</p>
                <a href="{{ route('history') }}" class="text-xs font-medium" style="color: var(--eco-primary)">Lihat semua →</a>
            </div>
            <div class="space-y-2">
                @forelse($recentTransactions as $trx)
                    @php
                        $slug  = $trx['category']['slug'] ?? 'default';
                        $emoji = match($slug) {
                            'food'        => '🍜',
                            'transport'   => '🚗',
                            'fashion'     => '👕',
                            'electricity' => '⚡',
                            'fuel'        => '⛽',
                            'flight'      => '✈️',
                            default       => '📦',
                        };
                        $co2e  = $trx['co2e'] ?? 0;
                        $level = $co2e <= 0.5 ? 'success' : ($co2e <= 2.0 ? 'warning' : 'danger');
                    @endphp
                    <div class="eco-history-item">
                        <div class="eco-history-icon" style="background: var(--eco-overlay)">{{ $emoji }}</div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate" style="color: var(--eco-text-primary)">{{ $trx['merchant_name'] }}</p>
                            <p class="text-xs" style="color: var(--eco-text-secondary)">{{ $trx['category']['name'] ?? 'Uncategorized' }}</p>
                        </div>
                        <span class="eco-badge eco-badge--{{ $level }} text-xs">{{ $co2e }} kg</span>
                    </div>
                @empty
                    <p class="text-sm" style="color: var(--eco-text-secondary)">Belum ada transaksi. Mulai dari kalkulator!</p>
                @endforelse
            </div>
        </div>

    </div>

</div>
