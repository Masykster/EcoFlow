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
            <div class="eco-insight-badge {{ $badge['earned'] ? 'earned' : 'locked' }} p-6 flex flex-col items-center justify-between min-h-[220px]">
                <div class="badge-icon w-16 h-16 flex items-center justify-center">
                    @switch($badge['icon'])
                        @case('mrt')
                            <svg class="w-12 h-12 text-[#2D5F50] mx-auto {{ $badge['earned'] ? '' : 'opacity-40 grayscale' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <rect x="4" y="3" width="16" height="15" rx="2" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8M8 11h8M6 18l-2 3M18 18l2 3M12 18v-3" />
                            </svg>
                            @break
                        @case('vegetarian')
                            <svg class="w-12 h-12 text-[#2D5F50] mx-auto {{ $badge['earned'] ? '' : 'opacity-40 grayscale' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" />
                            </svg>
                            @break
                        @case('carbon')
                            <svg class="w-12 h-12 text-[#2D5F50] mx-auto {{ $badge['earned'] ? '' : 'opacity-40 grayscale' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M12 3C8.5 7 5.5 11 5.5 15c0 3.5 2.5 6 6.5 6m0-18c3.5 4 6.5 8 6.5 12 0 3.5-2.5 6-6.5 6" />
                            </svg>
                            @break
                        @case('warrior')
                            <svg class="w-12 h-12 text-[#2D5F50] mx-auto {{ $badge['earned'] ? '' : 'opacity-40 grayscale' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10m0-20a15.3 15.3 0 00-4 10 15.3 15.3 0 004 10M2 12h20" />
                            </svg>
                            @break
                    @endswitch
                </div>
                <div class="text-center mt-2 flex-1">
                    <p class="font-bold text-sm" style="color: var(--eco-text-primary)">{{ $badge['name'] }}</p>
                    <p class="text-xs mt-1 leading-snug" style="color: var(--eco-text-secondary)">{{ $badge['desc'] }}</p>
                </div>
                <div class="mt-4">
                    @if($badge['earned'])
                        <span class="eco-badge eco-badge--success text-xs flex items-center justify-center gap-1 py-1 px-3 rounded-full">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Diraih</span>
                        </span>
                    @else
                        <span class="eco-badge text-xs flex items-center justify-center gap-1 py-1 px-3 rounded-full" style="background: #F0F0F0; color: var(--eco-text-secondary);">
                            <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                <path d="M7 11V7a5 5 0 0110 0v4" />
                            </svg>
                            <span>Terkunci</span>
                        </span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

</div>
