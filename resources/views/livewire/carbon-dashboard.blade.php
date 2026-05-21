<div class="flex flex-col lg:flex-row gap-8 p-6 lg:p-8 bg-[#F8F9FA] dark:bg-[#0D1512] min-h-screen text-[#111827] dark:text-white" x-data="dashboardCharts({
    dailyEmissions: @js($dailyEmissions),
    byCategory: @js($byCategory),
    totalCo2e: {{ $totalCo2e }}
})">

    {{-- ── LEFT SIDEBAR (BESPOKE WIDGETS) ──────────────────────────────────────────────────── --}}
    <div class="w-full lg:w-80 shrink-0 space-y-6">

        {{-- Feature 1: Carbon Budget Progress (Premium Card) --}}
        <div class="bg-white/70 dark:bg-[#1E2623]/35 backdrop-blur-md border border-white/60 dark:border-white/[0.05] rounded-[24px] p-6 shadow-[0_8px_30px_rgba(0,0,0,0.02)] dark:shadow-[0_8px_30px_rgba(0,0,0,0.2)]">
            <div class="flex items-center justify-between mb-4">
                <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Anggaran Karbon</p>
                <button wire:click="openBudgetModal" class="text-xs text-[#2D5F50] dark:text-[#A3D9A5] hover:opacity-80 transition-opacity font-bold flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    Ubah
                </button>
            </div>
            <div class="text-center my-4">
                <span class="text-4xl font-black tracking-tight {{ $budgetStatus === 'danger' ? 'text-[#E67E5D]' : ($budgetStatus === 'warning' ? 'text-amber-500' : 'text-[#2D5F50] dark:text-[#A3D9A5]') }}">{{ $monthlyUsed }}</span>
                <span class="text-xs text-gray-400 font-medium"> / {{ $monthlyLimit }} kg CO₂e</span>
            </div>
            {{-- Sleek Progress Bar --}}
            <div class="w-full h-2.5 bg-gray-100 dark:bg-zinc-800/60 rounded-full overflow-hidden relative">
                <div class="h-full rounded-full transition-all duration-1000 ease-out shadow-sm"
                     style="width: {{ min($budgetPercent, 100) }}%"
                     class="{{ $budgetStatus === 'danger' ? 'bg-[#E67E5D]' : ($budgetStatus === 'warning' ? 'bg-amber-400' : 'bg-[#2D5F50]') }}"></div>
            </div>
            <div class="flex justify-between items-center mt-3 text-[11px] font-semibold text-gray-400 dark:text-gray-500">
                <span>{{ $budgetPercent }}% Terpakai</span>
                <span>Sisa: {{ max($monthlyLimit - $monthlyUsed, 0) }} kg</span>
            </div>

            @if($budgetStatus === 'warning')
                <div class="mt-4 p-3 bg-amber-500/10 border border-amber-500/20 rounded-xl flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-[11px] text-amber-600 dark:text-amber-400 font-medium leading-relaxed">Mendekati batas limit emisi bulanan kamu. Coba kurangi konsumsi energi harian.</p>
                </div>
            @elseif($budgetStatus === 'danger')
                <div class="mt-4 p-3 bg-[#E67E5D]/10 border border-[#E67E5D]/20 rounded-xl flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-[#E67E5D] shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-[11px] text-[#E67E5D] dark:text-red-400 font-medium leading-relaxed">Anggaran emisi bulanan terlampaui! Disarankan melakukan aksi penyeimbangan (offset).</p>
                </div>
            @endif
        </div>

        {{-- Feature 2: AI Forecast (Minimalist predictive widget) --}}
        <div class="bg-white/70 dark:bg-[#1E2623]/35 backdrop-blur-md border border-white/60 dark:border-white/[0.05] rounded-[24px] p-6 shadow-[0_8px_30px_rgba(0,0,0,0.02)]">
            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#2D5F50] dark:text-[#A3D9A5] shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 21l-.813-5.096L3.096 15l5.096-.813L9 9.096l.813 5.096 5.096.813-5.096.813zM19.096 3.096l.813 5.096 5.096.813-5.096.813L19 14.904l-.813-5.096-5.096-.813 5.096-.813L19 3.096z"/>
                </svg>
                AI Lifestyle Forecaster
            </p>
            @if($forecastEndMonth > 0)
                <div class="text-center my-4">
                    <p class="text-[11px] font-semibold text-gray-400 dark:text-gray-500 mb-1">Estimasi Akhir Bulan</p>
                    <span class="text-3xl font-black tracking-tight {{ $forecastOverBudget ? 'text-[#E67E5D]' : 'text-[#2D5F50] dark:text-[#A3D9A5]' }}">{{ $forecastEndMonth }}</span>
                    <span class="text-xs text-gray-400 font-bold"> kg</span>
                </div>
                @if($forecastOverBudget)
                    <div class="p-3 bg-[#E67E5D]/10 border border-[#E67E5D]/20 rounded-xl text-center">
                        <p class="text-[11px] text-[#E67E5D] dark:text-red-400 font-semibold">Tren hidupmu saat ini akan melebihi anggaran karbon sebesar {{ round($forecastEndMonth - $monthlyLimit, 1) }} kg.</p>
                    </div>
                @else
                    <div class="p-3 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-center">
                        <p class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold">Hebat! Tren emisi aman, diprediksi menyisakan {{ round($monthlyLimit - $forecastEndMonth, 1) }} kg di akhir bulan.</p>
                    </div>
                @endif
            @else
                <div class="py-6 text-center text-gray-400 dark:text-gray-500">
                    <svg class="w-8 h-8 mx-auto opacity-40 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-xs font-medium">Memerlukan minimal data 3 hari untuk kalkulasi prediksi AI.</p>
                </div>
            @endif
        </div>

        <div class="bg-white/70 dark:bg-[#1E2623]/35 backdrop-blur-md border border-white/60 dark:border-white/[0.05] rounded-[24px] p-6 shadow-[0_8px_30px_rgba(0,0,0,0.02)]">
            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#2D5F50] dark:text-[#A3D9A5] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 00.495-7.467 5.99 5.99 0 00-1.925-3.546 5.974 5.974 0 01-2.133-1A3.75 3.75 0 003 7.5V18a3.75 3.75 0 003.75 3.75h5.25m3-3a3.75 3.75 0 01-.495-7.467 5.99 5.99 0 011.925-3.546 5.974 5.974 0 002.133-1A3.75 3.75 0 0121 7.5V18a3.75 3.75 0 01-3.75 3.75h-5.25"/>
                </svg>
                Rekomendasi Cerdas AI
            </p>
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 shrink-0 bg-[#A3D9A5]/20 dark:bg-[#A3D9A5]/10 rounded-2xl flex items-center justify-center text-[#2D5F50] dark:text-[#A3D9A5] shadow-inner">
                    @if($smartRecommendation['icon'] === 'fuel')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 19v-6a2 2 0 00-2-2h-3V5a2 2 0 00-2-2H6a2 2 0 00-2 2v14M14 19h5M3 19h10m-3-7v-3m0 0H8v3h2z"/></svg>
                    @elseif($smartRecommendation['icon'] === 'bolt')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    @elseif($smartRecommendation['icon'] === 'plane')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    @elseif($smartRecommendation['icon'] === 'utensils')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    @elseif($smartRecommendation['icon'] === 'recycle')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18.5M4 9h4.5"/></svg>
                    @elseif($smartRecommendation['icon'] === 'car')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0zM4 9h16l-2-5H6L4 9zm0 0v6h16V9"/></svg>
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    @endif
                </div>
                <div class="space-y-1">
                    <p class="text-xs font-bold text-gray-800 dark:text-gray-200">{{ $smartRecommendation['action'] }}</p>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 leading-relaxed">{{ $smartRecommendation['detail'] }}</p>
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 mt-2 rounded-full bg-[#2D5F50]/10 text-[#2D5F50] dark:text-[#A3D9A5] text-[10px] font-bold">
                        <svg class="w-3.5 h-3.5 text-[#2D5F50] dark:text-[#A3D9A5] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-1.2 3.6-3 6.6-6 9 3 2.4 4.8 5.4 6 9 1.2-3.6 3-6.6 6-9-3-2.4-4.8-5.4-6-9z"/>
                        </svg>
                        Hemat: {{ $smartRecommendation['saving'] }} kg CO₂e
                    </div>
                </div>
            </div>
        </div>

        {{-- Feature 4: Virtual Eco-Home (High Fidelity Custom SVG Widget) --}}
        <div class="bg-white/70 dark:bg-[#1E2623]/35 backdrop-blur-md border border-white/60 dark:border-white/[0.05] rounded-[24px] p-6 shadow-[0_8px_30px_rgba(0,0,0,0.02)] overflow-hidden">
            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#2D5F50] dark:text-[#A3D9A5] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.905 0-5.64-.78-8.006-2.141m16.012 0a8.97 8.97 0 00-1.558-2.23M3.994 8.359a8.97 8.97 0 011.558-2.23"/>
                </svg>
                Pulau EcoFlow
            </p>
            <div class="relative w-full h-44 rounded-2xl overflow-hidden shadow-inner border border-white/20 dark:border-white/[0.03]"
                 x-data="{ level: {{ $ecoHomeLevel }}, state: '{{ $ecoHomeState }}' }">
                
                {{-- Dynamic Environment Sky Background --}}
                <div class="absolute inset-0 transition-colors duration-1000"
                     :class="{
                        'bg-gradient-to-b from-emerald-300/40 to-sky-100/40': state === 'thriving',
                        'bg-gradient-to-b from-teal-200/40 to-blue-50/40': state === 'healthy',
                        'bg-gradient-to-b from-amber-200/40 to-orange-50/40': state === 'stressed',
                        'bg-gradient-to-b from-orange-300/30 to-red-50/30': state === 'struggling',
                        'bg-gradient-to-b from-zinc-500/30 to-red-100/20': state === 'critical'
                     }"></div>
                
                {{-- Ambient Light Orb --}}
                <div class="absolute top-2 right-4 transition-all duration-1000"
                     :class="state === 'thriving' || state === 'healthy' ? 'opacity-100 scale-100' : 'opacity-20 scale-75'">
                    <div class="w-10 h-10 rounded-full bg-amber-300 shadow-[0_0_20px_rgba(252,211,77,0.6)]"></div>
                </div>
                
                {{-- Storm Clouds overlay --}}
                <div class="absolute top-2 left-4 transition-opacity duration-1000"
                     :class="state === 'stressed' || state === 'struggling' || state === 'critical' ? 'opacity-70' : 'opacity-0'">
                    <svg class="w-14 h-8 text-gray-400 dark:text-gray-600" viewBox="0 0 56 32" fill="currentColor"><ellipse cx="20" cy="22" rx="16" ry="10"/><ellipse cx="36" cy="18" rx="14" ry="12"/><ellipse cx="28" cy="24" rx="20" ry="8"/></svg>
                </div>

                {{-- Premium Vector Floating Island --}}
                <div class="absolute bottom-[-10px] left-1/2 -translate-x-1/2 w-44">
                    <svg viewBox="0 0 160 80" class="w-full drop-shadow-[0_10px_15px_rgba(0,0,0,0.1)]">
                        <ellipse cx="80" cy="55" rx="75" ry="22" fill="#8B6914" opacity="0.6"/>
                        <ellipse cx="80" cy="48" rx="70" ry="18"
                                 :fill="state === 'thriving' ? '#22c55e' : state === 'healthy' ? '#4ade80' : state === 'stressed' ? '#eab308' : '#a1a1aa'"/>
                    </svg>
                </div>

                {{-- Dynamic Ecosystem Trees --}}
                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-1.5 items-end">
                    <template x-for="i in 5">
                        <div class="flex flex-col items-center transition-all duration-700"
                             :class="i <= Math.ceil(level/20) ? 'opacity-100 scale-100' : 'opacity-20 scale-50'"
                             :style="'transform-origin: bottom; height:' + (24 + i*4) + 'px'">
                            <div class="rounded-full transition-colors duration-1000 shadow-sm"
                                 :class="state === 'critical' ? 'bg-zinc-500' : state === 'struggling' ? 'bg-amber-600' : 'bg-emerald-600'"
                                 :style="'width:' + (10+i*2) + 'px; height:' + (12+i*3) + 'px'"></div>
                            <div class="w-1 bg-[#5c4033] rounded-b" :style="'height:' + (6+i) + 'px'"></div>
                        </div>
                    </template>
                </div>
            </div>
            
            <div class="mt-4 text-center">
                <p class="text-xs font-bold uppercase tracking-wider" :class="{
                    'text-emerald-600 dark:text-emerald-400': '{{ $ecoHomeState }}' === 'thriving',
                    'text-green-500 dark:text-green-400': '{{ $ecoHomeState }}' === 'healthy',
                    'text-amber-500 dark:text-amber-400': '{{ $ecoHomeState }}' === 'stressed',
                    'text-orange-500 dark:text-orange-400': '{{ $ecoHomeState }}' === 'struggling',
                    'text-[#E67E5D]': '{{ $ecoHomeState }}' === 'critical'
                }">
                    {{ match($ecoHomeState) {
                        'thriving' => 'Pulau Subur & Asri',
                        'healthy' => 'Pulau Sehat',
                        'stressed' => 'Pulau Mulai Kering',
                        'struggling' => 'Pulau Mendung',
                        'critical' => 'Pulau Kritis',
                    } }}
                </p>
                <div class="flex justify-between items-center text-[10px] text-gray-400 dark:text-gray-500 font-semibold mt-2 px-1">
                    <span>Ecosystem Level</span>
                    <span>{{ $ecoHomeLevel }} / 100</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── MAIN CONTENT AREA ──────────────────────────────────────────────────── --}}
    <div class="flex-1 space-y-8 min-w-0">

        {{-- Header + Premium Pill Period Switcher --}}
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-black tracking-tight text-[#1E3F35] dark:text-white">Dashboard Karbon</h1>
                <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1">EcoFlow • Sustainable Analytics</p>
            </div>
            <div class="flex bg-white/60 dark:bg-[#1E2623]/20 border border-white/50 dark:border-white/[0.04] p-1 rounded-full shadow-sm">
                @foreach(['daily' => 'Hari Ini', 'weekly' => 'Minggu Ini', 'monthly' => 'Bulan Ini'] as $key => $label)
                    <button
                        wire:click="setPeriod('{{ $key }}')"
                        class="px-5 py-2 rounded-full text-xs font-bold transition-all duration-300 {{ $period === $key ? 'bg-[#1E3F35] text-white shadow-sm dark:bg-[#A3D9A5] dark:text-[#1E3F35]' : 'text-gray-500 hover:text-gray-800 dark:hover:text-white' }}"
                    >{{ $label }}</button>
                @endforeach
            </div>
        </div>

        {{-- Bento Grid Layout --}}
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

            {{-- 1. Total CO2e Banner (Premium Glassmorphic Hero Card) --}}
            <div class="md:col-span-8 bg-gradient-to-tr from-[#1E3F35] via-[#244C3F] to-[#2D5F50] rounded-[28px] p-8 shadow-[0_20px_40px_rgba(30,63,53,0.15)] text-white relative overflow-hidden flex flex-col justify-between min-h-[220px]">
                <!-- Abstract glowing graphic in background -->
                <div class="absolute right-[-10%] top-[-20%] w-[300px] h-[300px] rounded-full bg-[#A3D9A5]/20 blur-[60px] pointer-events-none"></div>
                <div class="absolute left-[30%] bottom-[-20%] w-[200px] h-[200px] rounded-full bg-emerald-400/10 blur-[50px] pointer-events-none"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center gap-2 text-[#A3D9A5] text-[11px] font-bold uppercase tracking-widest">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#A3D9A5] animate-pulse"></span>
                        Akumulasi Emisi Kamu
                    </div>
                    <div class="mt-4 flex items-baseline gap-2">
                        <span class="text-6xl font-black tracking-tight"
                              x-data="{ count: 0 }"
                              x-init="
                                  let target = {{ $totalCo2e }};
                                  let step = target / 35;
                                  let interval = setInterval(() => {
                                      count += step;
                                      if(count >= target) { count = target; clearInterval(interval); }
                                  }, 25);
                              "
                              x-text="count.toFixed(2)">0.00</span>
                        <span class="text-lg font-bold text-[#A3D9A5] uppercase">kg CO₂e</span>
                    </div>
                </div>

                <div class="relative z-10 mt-6 pt-4 border-t border-white/10 flex justify-between items-center">
                    @php $isWarning = $totalCo2e > $targetEmission; @endphp
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ $isWarning ? 'bg-amber-500/20 text-amber-300' : 'bg-emerald-500/20 text-emerald-300' }} border border-white/10">
                        @if($isWarning)
                            <svg class="w-3 h-3 text-amber-300 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            Perlu Perhatian
                        @else
                            <svg class="w-3 h-3 text-emerald-300 shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Jejak Karbon Terkendali
                        @endif
                    </span>
                    <span class="text-[10px] font-semibold text-white/50">Target IPCC: {{ $targetEmission }} kg</span>
                </div>
            </div>

            {{-- 2. Clean Green Nudges (Bento Card Side) --}}
            <div class="md:col-span-4 bg-white/70 dark:bg-[#1E2623]/35 backdrop-blur-md border border-white/60 dark:border-white/[0.05] rounded-[28px] p-6 shadow-[0_8px_30px_rgba(0,0,0,0.02)] flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400 text-xs font-bold uppercase tracking-wider mb-3">
                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        Green Nudge
                    </div>
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white leading-snug mb-1">{{ $dynamicNudge['title'] }}</h3>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 leading-relaxed">{{ $dynamicNudge['message'] }}</p>
                </div>
                <div class="mt-4">
                    <a href="{{ $dynamicNudge['link'] }}" class="w-full block bg-[#1E3F35] dark:bg-[#A3D9A5] dark:text-[#1E3F35] hover:opacity-90 text-white text-xs font-bold py-2.5 rounded-2xl text-center shadow-md transition-all">
                        {{ $dynamicNudge['cta'] }}
                    </a>
                </div>
            </div>

            {{-- 3. Trend Line Chart (Bento Card Full width) --}}
            <div class="md:col-span-12 bg-white/70 dark:bg-[#1E2623]/35 backdrop-blur-md border border-white/60 dark:border-white/[0.05] rounded-[28px] p-6 shadow-[0_8px_30px_rgba(0,0,0,0.02)]">
                <div class="flex items-center justify-between flex-wrap gap-2 mb-4">
                    <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Tren Emisi Harian (30 Hari Terakhir)</p>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-1 text-[10px] text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-500/10 px-2 py-0.5 rounded">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Di bawah target
                        </span>
                        <span class="inline-flex items-center gap-1 text-[10px] text-red-500 font-bold bg-red-500/10 px-2 py-0.5 rounded">
                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> Over-limit
                        </span>
                    </div>
                </div>
                <div class="relative w-full h-60">
                    <canvas id="trendChart" wire:ignore></canvas>
                </div>
            </div>

            {{-- 4. Category breakdown donut chart (Half width) --}}
            <div class="md:col-span-6 bg-white/70 dark:bg-[#1E2623]/35 backdrop-blur-md border border-white/60 dark:border-white/[0.05] rounded-[28px] p-6 shadow-[0_8px_30px_rgba(0,0,0,0.02)]">
                <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-6">Emisi per Kategori</p>
                <div class="flex flex-col sm:flex-row items-center gap-6 justify-center">
                    <div class="relative w-36 h-36 shrink-0">
                        <canvas id="donutChart" wire:ignore></canvas>
                    </div>
                    <div class="flex-1 space-y-3">
                        @if(!empty($byCategory))
                            <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase">Kontributor Utama</p>
                            <div class="space-y-1">
                                <p class="text-lg font-black text-gray-800 dark:text-gray-100">{{ $byCategory[0]['category'] }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold">{{ round($byCategory[0]['total_co2e'] / $totalCo2e * 100) }}% dari total jejak karbonmu.</p>
                            </div>
                        @else
                            <p class="text-xs text-gray-400 dark:text-gray-500">Belum ada data emisi terdaftar.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 5. Recent transactions (Half width) --}}
            <div class="md:col-span-6 bg-white/70 dark:bg-[#1E2623]/35 backdrop-blur-md border border-white/60 dark:border-white/[0.05] rounded-[28px] p-6 shadow-[0_8px_30px_rgba(0,0,0,0.02)] flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Transaksi Terbaru</p>
                        <a href="{{ route('history') }}" class="text-[10px] font-extrabold text-[#2D5F50] dark:text-[#A3D9A5] hover:opacity-80 transition-opacity uppercase tracking-wider flex items-center gap-1">
                            Semua Riwayat <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    <div class="space-y-2.5">
                        @forelse($recentTransactions as $trx)
                            @php
                                $slug  = $trx['category']['slug'] ?? 'default';
                                $co2e  = $trx['co2e'] ?? 0;
                                $catAvg = $trx['cat_avg'] ?? 0;
                                $trendDown = $co2e <= $catAvg;
                            @endphp
                            <div class="flex items-center gap-3 p-2 bg-white/30 dark:bg-black/10 rounded-xl hover:bg-white/50 dark:hover:bg-black/20 transition-colors">
                                <div class="w-10 h-10 shrink-0 flex items-center justify-center bg-[#A3D9A5]/15 dark:bg-[#A3D9A5]/5 text-[#2D5F50] dark:text-[#A3D9A5] rounded-xl shadow-inner">
                                    @if($slug === 'bahan_bakar')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 19v-6a2 2 0 00-2-2h-3V5a2 2 0 00-2-2H6a2 2 0 00-2 2v14M14 19h5M3 19h10m-3-7v-3m0 0H8v3h2z"/></svg>
                                    @elseif($slug === 'elektronik')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    @elseif($slug === 'penerbangan')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                    @elseif($slug === 'makanan')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    @elseif($slug === 'sampah')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    @elseif($slug === 'kendaraan')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0zM4 9h16l-2-5H6L4 9zm0 0v6h16V9"/></svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-extrabold text-gray-800 dark:text-gray-200 truncate">{{ $trx['merchant_name'] }}</p>
                                    <p class="text-[10px] font-semibold text-gray-400 dark:text-gray-500">{{ $trx['category']['name'] ?? 'Uncategorized' }}</p>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="text-xs font-black text-gray-800 dark:text-white">{{ $co2e }} kg</span>
                                    <div class="flex items-center justify-end gap-0.5 mt-0.5">
                                        @if($trendDown)
                                            <svg class="w-2.5 h-2.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                            <span class="text-[9px] text-emerald-500 font-bold uppercase tracking-wide">Efisien</span>
                                        @else
                                            <svg class="w-2.5 h-2.5 text-[#E67E5D]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                            <span class="text-[9px] text-[#E67E5D] font-bold uppercase tracking-wide">Boros</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-gray-400 dark:text-gray-500">
                                <p class="text-xs font-medium">Belum ada aktivitas karbon terdaftar.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Budget settings Modal --}}
    @if($showBudgetModal)
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" wire:click.self="closeBudgetModal">
        <div class="bg-white dark:bg-[#1E2623] border border-white/20 dark:border-white/[0.04] rounded-[24px] p-6 w-full max-w-md shadow-2xl transition-all duration-300">
            <div class="flex items-center gap-2.5 mb-3">
                <svg class="w-5 h-5 text-[#2D5F50] dark:text-[#A3D9A5] shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <circle cx="12" cy="12" r="6"/>
                    <circle cx="12" cy="12" r="2"/>
                </svg>
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Atur Anggaran Karbon</h3>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4 leading-relaxed">Tentukan batas maksimal emisi karbon kamu per bulan (dalam kg CO₂e).</p>
            <input type="number" wire:model="newBudgetLimit" min="1" step="5"
                   class="w-full rounded-xl border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-[#2D5F50] focus:border-[#2D5F50] text-xl font-black text-center py-3 mb-5 dark:text-white">
            <div class="flex gap-3">
                <button wire:click="closeBudgetModal" class="flex-1 py-2.5 rounded-xl border border-gray-200 dark:border-zinc-700 text-gray-600 dark:text-gray-400 font-semibold text-xs hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors">Batal</button>
                <button wire:click="saveBudgetLimit" class="flex-1 py-2.5 rounded-xl bg-[#2D5F50] dark:bg-[#A3D9A5] text-white dark:text-[#1E3F35] font-bold text-xs hover:opacity-90 transition-opacity">Simpan</button>
            </div>
        </div>
    </div>
    @endif

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('dashboardCharts', (data) => ({
        trendChart: null,
        donutChart: null,
        init() {
            setTimeout(() => this.renderCharts(), 100);
            document.addEventListener('livewire:navigated', () => this.renderCharts());
            Livewire.hook('morph.updated', () => this.renderCharts());
        },
        renderCharts() {
            if(!window.Chart) return;
            this.renderTrend();
            this.renderDonut();
        },
        renderTrend() {
            const ctx = document.getElementById('trendChart');
            if(!ctx) return;
            if(this.trendChart) this.trendChart.destroy();
            const labels = data.dailyEmissions.map(d => d.date);
            const values = data.dailyEmissions.map(d => d.total);
            const colors = values.map(v => v > 2.0 ? '#E67E5D' : '#A3D9A5');
            this.trendChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{ 
                        label: 'Emisi (kg)', 
                        data: values, 
                        borderColor: '#2D5F50', 
                        borderWidth: 2.5,
                        backgroundColor: 'rgba(163,217,165,0.15)', 
                        pointBackgroundColor: colors, 
                        pointBorderColor: '#fff', 
                        pointBorderWidth: 1.5,
                        pointRadius: 4, 
                        pointHoverRadius: 6,
                        fill: true, 
                        tension: 0.35 
                    }]
                },
                options: {
                    responsive: true, 
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { borderDash: [6, 6], color: 'rgba(156,163,175,0.12)' },
                            ticks: { font: { family: 'Satoshi, Inter, sans-serif', size: 10 } }
                        },
                        x: { 
                            grid: { display: false },
                            ticks: { font: { family: 'Satoshi, Inter, sans-serif', size: 10 } }
                        }
                    }
                }
            });
        },
        renderDonut() {
            const ctx = document.getElementById('donutChart');
            if(!ctx || data.byCategory.length === 0) return;
            if(this.donutChart) this.donutChart.destroy();
            const labels = data.byCategory.map(c => c.category);
            const values = data.byCategory.map(c => c.total_co2e);
            const colorMap = { 'bahan_bakar': '#E67E5D', 'elektronik': '#FCD34D', 'penerbangan': '#60A5FA', 'kendaraan': '#A3D9A5', 'makanan': '#34D399', 'sampah': '#9CA3AF' };
            const bgColors = data.byCategory.map(c => colorMap[c.slug] || '#2D5F50');
            this.donutChart = new Chart(ctx, {
                type: 'doughnut',
                data: { labels: labels, datasets: [{ data: values, backgroundColor: bgColors, borderWidth: 0, hoverOffset: 4 }] },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false, 
                    cutout: '76%', 
                    plugins: { legend: { display: false } } 
                }
            });
        }
    }));
});
</script>
