<div class="p-6 space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold" style="color: var(--eco-primary)">Pencapaian</h1>
        <p class="text-sm mt-1" style="color: var(--eco-text-secondary)">Badge dan poin EcoStep kamu</p>
    </div>

    {{-- Points Card --}}
    <div class="bento-card" style="background: linear-gradient(135deg, var(--eco-primary) 0%, #2D5F50 100%);">
        <p class="eco-number-label" style="color: rgba(163,217,165,0.8)">Total Poin EcoStep</p>
        <div class="flex items-end gap-2 mt-2">
            <span class="eco-number" style="color: #fff; font-size: 2.5rem;">{{ number_format($points) }}</span>
            <span class="text-lg mb-1" style="color: rgba(255,255,255,0.6)">poin</span>
        </div>
        <p class="text-xs mt-2" style="color: rgba(255,255,255,0.5)">Dapatkan poin dengan memilih transportasi rendah emisi & makanan nabati</p>
    </div>

    {{-- Badge Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($allBadges as $badge)
            <div class="eco-insight-badge {{ $badge['earned'] ? 'earned' : 'locked' }}">
                <div class="badge-icon">{{ $badge['icon'] }}</div>
                <p class="font-bold text-sm mt-1" style="color: var(--eco-text-primary)">{{ $badge['name'] }}</p>
                <p class="text-xs text-center" style="color: var(--eco-text-secondary)">{{ $badge['desc'] }}</p>
                @if($badge['earned'])
                    <span class="eco-badge eco-badge--success text-xs">✓ Diraih</span>
                @else
                    <span class="eco-badge text-xs" style="background: #F0F0F0; color: var(--eco-text-secondary);">🔒 Terkunci</span>
                @endif
            </div>
        @endforeach
    </div>

</div>
