<div class="flex flex-col gap-4 h-full min-h-0 text-white" 
     x-data="dashboardCharts({
         dailyEmissions: @js($dailyEmissions),
         byCategory: @js($byCategory),
         totalCo2e: {{ $totalCo2e }}
     })">

    {{-- Row 1: Title & Period Switcher --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 shrink-0">
        <h2 class="text-3xl font-black tracking-tight text-white leading-none">
            Carbon Analytics
        </h2>
        
        <!-- Period Switcher -->
        <div class="flex bg-[#1d2722]/80 border border-white/5 p-1 rounded-full shadow-inner">
            @foreach(['daily' => 'Hari Ini', 'weekly' => 'Minggu Ini', 'monthly' => 'Bulan Ini'] as $key => $label)
                <button
                    wire:click="setPeriod('{{ $key }}')"
                    class="px-5 py-2 rounded-full text-xs font-bold transition-all duration-300 {{ $period === $key ? 'bg-[#2b3731] text-[#A3D9A5] shadow-sm border border-white/5' : 'text-gray-400 hover:text-white' }}"
                >{{ $label }}</button>
            @endforeach
        </div>
    </div>

    {{-- Row 2: Gauge & Main Numeric Stats --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-center border-t border-white/5 pt-4 shrink-0">
        <!-- Circular Budget Gauge -->
        <div class="lg:col-span-4 flex items-center justify-start gap-4">
            <div class="relative w-16 h-16 flex items-center justify-center shrink-0">
                <svg class="absolute inset-0 w-full h-full transform -rotate-90">
                    <circle cx="32" cy="32" r="28" class="stroke-current text-white/5" stroke-width="4.5" fill="transparent" stroke-dasharray="4, 3" />
                    <circle cx="32" cy="32" r="28" class="stroke-current text-[#A3D9A5]" stroke-width="4.5" fill="transparent"
                            stroke-dasharray="175" stroke-dashoffset="{{ 175 - (175 * min($budgetPercent, 100) / 100) }}" />
                </svg>
                <div class="text-center">
                    <span class="text-sm font-black text-white leading-none block">{{ round($budgetPercent) }}%</span>
                    <span class="text-[6px] font-bold text-gray-400 uppercase tracking-widest leading-none block mt-0.5">Budget</span>
                </div>
            </div>
            <div class="text-left">
                <span class="block text-[9px] font-bold text-gray-500 uppercase tracking-wider leading-none">Anggaran Terpakai</span>
                <span class="text-xs font-black text-white mt-1 block">{{ $monthlyUsed }} / {{ $monthlyLimit }} kg</span>
            </div>
        </div>

        <!-- Stats Stack -->
        <div class="lg:col-span-8 grid grid-cols-3 gap-4 text-left border-l border-white/5 pl-6">
            <div>
                <span class="block text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Emisi</span>
                <span class="text-xl md:text-2xl font-black text-white tracking-tight">
                    {{ $totalCo2e }} <span class="text-xs font-bold text-[#A3D9A5]">kg</span>
                </span>
            </div>
            <div>
                <span class="block text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-1 flex items-center gap-1">
                    Limit Bulanan
                    <button wire:click="openBudgetModal" class="text-[#A3D9A5] hover:underline cursor-pointer">
                        <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </button>
                </span>
                <span class="text-xl md:text-2xl font-black text-white tracking-tight">
                    {{ $monthlyLimit }} <span class="text-xs font-bold text-[#A3D9A5]">kg</span>
                </span>
            </div>
            <div>
                <span class="block text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-1">Prediksi AI</span>
                <span class="text-xl md:text-2xl font-black text-white tracking-tight">
                    {{ $forecastEndMonth > 0 ? $forecastEndMonth : '0.00' }} <span class="text-xs font-bold text-[#A3D9A5]">kg</span>
                </span>
            </div>
        </div>
    </div>

    {{-- Row 3: Category Pills --}}
    <div class="flex flex-wrap items-center justify-between gap-4 border-t border-white/5 pt-4 shrink-0">
        <div class="flex flex-wrap gap-2 items-center">
            <span class="px-3.5 py-1.5 rounded-full text-[10px] font-bold bg-[#1b2520] border border-[#A3D9A5]/30 text-[#A3D9A5]">Semua Kategori</span>
            @foreach(array_slice($byCategory, 0, 5) as $cat)
                <span class="px-3.5 py-1.5 rounded-full text-[10px] font-bold bg-[#151c19]/60 border border-white/5 text-gray-400">
                    {{ $cat['category'] }}
                </span>
            @endforeach
            <button class="w-6 h-6 rounded-full bg-[#1b2520] border border-white/10 flex items-center justify-center text-gray-400 hover:text-white transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
            </button>
        </div>
        
        <div class="flex gap-2">
            <button class="w-8 h-8 rounded-lg bg-[#1b2520] border border-white/5 flex items-center justify-center text-gray-400 hover:text-white transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 10.742l-5.183-5.184m0 0l-1.077 1.077m1.077-1.077l5.183 5.184m1.183-1.184h6.842m0 0l5.184 5.184m0 0l1.077-1.077m-1.077 1.077l-5.184-5.184m-1.183 1.184v6.842M8.684 13.258l-5.183 5.184m0 0l-1.077-1.077m1.077 1.077l5.183-5.184" />
                </svg>
            </button>
            <button wire:click="openBudgetModal" 
                    class="w-8 h-8 rounded-lg bg-[#1b2520] border border-white/5 flex items-center justify-center text-gray-400 hover:text-white transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Row 4: Grid Layout (Main Body Content) --}}
    <div class="flex-1 min-h-0 grid grid-cols-12 gap-4">
        <!-- Left Side Widget Container (Spans 8 cols) -->
        <div class="col-span-8 flex flex-col gap-4 h-full min-h-0">
            <!-- Process Milestone Pipeline -->
            <div class="relative flex items-center justify-between w-full px-12 py-2.5 border border-white/5 bg-[#141d19]/40 rounded-2xl shrink-0">
                <div class="absolute left-16 right-16 top-1/2 h-[1px] bg-white/10 z-0"></div>
                
                <!-- Node 1 -->
                <div class="relative z-10 flex flex-col items-center gap-1">
                    <div class="w-8 h-8 rounded-full bg-[#1b2520] border border-white/10 flex items-center justify-center text-[#A3D9A5] shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>
                        </svg>
                    </div>
                    <span class="text-[8px] font-bold text-gray-500 uppercase tracking-widest leading-none">Atur Limit</span>
                </div>

                <!-- Node 2 -->
                <div class="relative z-10 flex flex-col items-center gap-1">
                    <div class="w-8 h-8 rounded-full bg-[#1b2520] border border-white/10 flex items-center justify-center text-white shadow-md">
                        <div class="flex -space-x-1.5">
                            <div class="w-3.5 h-3.5 rounded-full bg-emerald-700 border border-[#1b2520] flex items-center justify-center text-[6px] font-bold">1</div>
                            <div class="w-3.5 h-3.5 rounded-full bg-teal-700 border border-[#1b2520] flex items-center justify-center text-[6px] font-bold">2</div>
                            <div class="w-3.5 h-3.5 rounded-full bg-[#A3D9A5] text-black border border-[#1b2520] flex items-center justify-center text-[6px] font-bold">+</div>
                        </div>
                    </div>
                    <span class="text-[8px] font-bold text-[#A3D9A5] uppercase tracking-widest leading-none">Catat Emisi</span>
                </div>

                <!-- Node 3 -->
                <div class="relative z-10 flex flex-col items-center gap-1">
                    <div class="w-8 h-8 rounded-full bg-[#1b2520] border border-white/10 flex items-center justify-center text-[#A3D9A5] shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3M3.994 8.359a8.97 8.97 0 011.558-2.23"/>
                        </svg>
                    </div>
                    <span class="text-[8px] font-bold text-gray-500 uppercase tracking-widest leading-none">Aksi Hijau</span>
                </div>
            </div>

            <!-- Three widgets grid (Takes remaining space) -->
            <div class="flex-1 min-h-0 grid grid-cols-3 gap-4">
                <!-- Widget 1: Calendar Grid -->
                <div class="bg-[#151d19]/40 border border-white/5 rounded-[2rem] p-4 flex flex-col justify-between h-full min-h-0">
                    <div>
                        <span class="block text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-2">Tanggal Pelacakan</span>
                        <div class="grid grid-cols-7 gap-1 text-center text-[8px]">
                            @for($i = 1; $i <= 28; $i++)
                                @php $isToday = $i == now()->day; @endphp
                                <div class="py-0.5 rounded-md font-bold {{ $isToday ? 'bg-[#A3D9A5] text-black shadow-sm' : 'text-gray-500' }}">
                                    {{ $i }}
                                </div>
                            @endfor
                        </div>
                    </div>
                    <div class="border-t border-white/5 pt-2 flex justify-between items-center text-[9px] text-gray-400 font-semibold">
                        <div>
                            <span class="text-[8px] text-gray-500 leading-none">Terakhir</span>
                            <span class="text-[10px] font-black text-white block mt-0.5">Hari ini</span>
                        </div>
                        <div class="text-right">
                            <span class="text-[8px] text-gray-500 leading-none">Aktivitas</span>
                            <span class="text-[10px] font-black text-white block mt-0.5">{{ count($recentTransactions) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Widget 2: Environment status -->
                <div class="bg-[#151d19]/40 border border-white/5 rounded-[2rem] p-4 flex flex-col justify-between h-full min-h-0">
                    <div>
                        <span class="block text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-2.5">Level Lingkungan</span>
                        <div class="flex flex-col gap-2">
                            <span class="px-2 py-1.5 rounded-lg text-center text-[10px] font-bold border border-white/5 uppercase tracking-wide bg-[#202b25]/80 text-[#A3D9A5] truncate leading-none">
                                @if($budgetStatus === 'safe')
                                    Lestari & Aman
                                @elseif($budgetStatus === 'warning')
                                    Mulai Kritis
                                @else
                                    Bahaya Kritis
                                @endif
                            </span>
                            <span class="px-2 py-1.5 rounded-lg text-center text-[10px] font-bold border border-white/5 uppercase tracking-wide bg-[#151c19]/60 text-gray-400 truncate leading-none">
                                {{ $ecoHomeState }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="border-t border-white/5 pt-2 text-center">
                        <div class="flex items-baseline gap-1 justify-center leading-none">
                            <span class="text-4xl font-black text-white tracking-tight">{{ $ecoHomeLevel }}</span>
                            <span class="text-[10px] font-bold text-gray-500">/100</span>
                        </div>
                        <span class="block text-[7px] uppercase tracking-widest text-[#A3D9A5] mt-1 font-bold">Skor Kelestarian</span>
                    </div>
                </div>

                <!-- Widget 3: Trend Chart integration -->
                <div class="bg-[#151d19]/40 border border-white/5 rounded-[2rem] p-4 flex flex-col justify-between h-full min-h-0">
                    <div>
                        <span class="block text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-2">Tren Emisi</span>
                    </div>
                    <div class="flex-1 min-h-0 relative w-full h-32 my-1">
                        <canvas id="trendChart" class="w-full h-full" wire:ignore></canvas>
                    </div>
                    <div class="border-t border-white/5 pt-2 flex justify-between items-center text-[9px] text-gray-400 font-semibold">
                        <div>
                            <span class="text-[8px] text-gray-500 leading-none">30 Hari Terakhir</span>
                            <span class="text-[10px] font-black text-white block mt-0.5">Emisi Terpantau</span>
                        </div>
                        <div class="w-6 h-6 rounded-full bg-[#1b2520] border border-white/10 flex items-center justify-center text-gray-400 hover:text-white transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side Stack Containers (Spans 4 cols) -->
        <div class="col-span-4 flex flex-col gap-4 h-full min-h-0">
            <!-- Card 1: AI Recommendation -->
            <div class="bg-[#151d19]/40 border border-white/5 rounded-[2.5rem] p-4 flex flex-col justify-between flex-1 min-h-0">
                <div class="flex justify-between items-start">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-[#A3D9A5]/15 border border-[#A3D9A5]/20 flex items-center justify-center text-[#A3D9A5] shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <div class="text-left">
                            <span class="block text-xs font-bold text-white leading-none">Rekomendasi AI</span>
                            <span class="block text-[7px] font-semibold text-gray-500 uppercase tracking-widest mt-0.5 leading-none">Saran Cerdas</span>
                        </div>
                    </div>
                    <button class="w-6 h-6 rounded-full bg-[#1b2520] border border-white/10 flex items-center justify-center text-gray-400 hover:text-white transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                    </button>
                </div>
                <div class="my-2 text-left flex-1 overflow-y-auto">
                    @if(!empty($smartRecommendation))
                        <p class="text-[10px] font-bold text-[#A3D9A5] leading-snug">{{ $smartRecommendation['action'] }}</p>
                        <p class="text-[9px] text-gray-400 leading-normal mt-1">{{ $smartRecommendation['detail'] }}</p>
                    @else
                        <p class="text-[9px] font-medium text-gray-500 leading-snug">Saran cerdas akan muncul saat data emisi tercatat.</p>
                    @endif
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-white/5 shrink-0" x-data="{ toggled: true }">
                    <span class="text-[8px] font-bold text-gray-500 uppercase tracking-wider">Potensi Hemat: {{ $smartRecommendation['saving'] ?? 0 }} kg</span>
                    <button @click="toggled = !toggled" class="relative inline-flex h-4 w-7 shrink-0 cursor-pointer rounded-full border border-transparent transition-colors duration-200 ease-in-out focus:outline-none" :class="toggled ? 'bg-[#A3D9A5]' : 'bg-[#2b3731]'">
                        <span class="pointer-events-none inline-block h-3 w-3 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="toggled ? 'translate-x-3' : 'translate-x-0'"></span>
                    </button>
                </div>
            </div>

            <!-- Card 2: Recent Transactions -->
            <div class="bg-[#151d19]/40 border border-white/5 rounded-[2.5rem] p-4 flex flex-col justify-between flex-1 min-h-0">
                <div class="flex justify-between items-start shrink-0">
                    <div class="text-left">
                        <span class="block text-xs font-bold text-white leading-none">Aktivitas Terbaru</span>
                        <span class="block text-[8px] font-semibold text-gray-500 uppercase tracking-widest mt-1">Riwayat Aktivitas</span>
                    </div>
                    <a href="{{ route('history') }}" class="text-[9px] font-extrabold text-[#A3D9A5] hover:opacity-80 transition-opacity uppercase tracking-wider flex items-center gap-1 leading-none" wire:navigate>
                        Semua
                    </a>
                </div>
                
                <div class="flex-1 min-h-0 overflow-y-auto space-y-2 mt-3 pr-1">
                    @forelse($recentTransactions as $trx)
                        @php
                            $slug = $trx['category']['slug'] ?? 'default';
                            $co2e = $trx['co2e'] ?? 0;
                        @endphp
                        <div class="flex items-center gap-2 p-1.5 bg-[#1b2520]/60 rounded-xl border border-white/5">
                            <div class="w-7 h-7 shrink-0 flex items-center justify-center bg-[#A3D9A5]/10 text-[#A3D9A5] rounded-lg border border-white/5">
                                @if($slug === 'bahan_bakar')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 19v-6a2 2 0 00-2-2h-3V5a2 2 0 00-2-2H6a2 2 0 00-2 2v14M14 19h5M3 19h10m-3-7v-3m0 0H8v3h2z"/></svg>
                                @elseif($slug === 'elektronik')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                @elseif($slug === 'penerbangan')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                @elseif($slug === 'makanan')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                @elseif($slug === 'sampah')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                @elseif($slug === 'kendaraan')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0zM4 9h16l-2-5H6L4 9zm0 0v6h16V9"/></svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0 text-left">
                                <p class="text-[9px] font-bold text-white truncate leading-none">{{ $trx['merchant_name'] }}</p>
                                <span class="text-[7px] font-semibold text-gray-500 mt-0.5 block leading-none">{{ $trx['category']['name'] ?? 'Uncategorized' }}</span>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-[9px] font-black text-white">{{ $co2e }} kg</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-gray-500">
                            <p class="text-[9px] font-medium leading-none">Belum ada aktivitas karbon.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Carbon Budget Limit modal settings --}}
    @if($showBudgetModal)
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" wire:click.self="closeBudgetModal">
        <div class="bg-[#121c17]/95 border border-white/20 rounded-[24px] p-6 w-full max-w-md shadow-2xl transition-all duration-300">
            <div class="flex items-center gap-2.5 mb-3">
                <svg class="w-5 h-5 text-[#A3D9A5] shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>
                </svg>
                <h3 class="text-lg font-bold text-white">Atur Anggaran Karbon</h3>
            </div>
            <p class="text-xs text-gray-400 mb-4 leading-relaxed font-semibold">Tentukan batas maksimal emisi karbon kamu per bulan (dalam kg CO₂e).</p>
            <input type="number" wire:model="newBudgetLimit" min="1" step="5"
                   class="w-full rounded-xl border-white/10 bg-[#0c1410] focus:ring-[#A3D9A5] focus:border-[#A3D9A5] text-xl font-black text-center py-3 mb-5 text-white">
            <div class="flex gap-3">
                <button wire:click="closeBudgetModal" class="flex-1 py-2.5 rounded-xl border border-white/10 text-gray-400 font-semibold text-xs hover:bg-[#1b2520] transition-colors">Batal</button>
                <button wire:click="saveBudgetLimit" class="flex-1 py-2.5 rounded-xl bg-[#A3D9A5] text-[#0c1410] font-bold text-xs hover:opacity-90 transition-opacity">Simpan</button>
            </div>
        </div>
    </div>
    @endif

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('dashboardCharts', (data) => ({
        trendChart: null,
        init() {
            setTimeout(() => this.renderCharts(), 100);
            document.addEventListener('livewire:navigated', () => this.renderCharts());
            Livewire.hook('morph.updated', () => this.renderCharts());
        },
        renderCharts() {
            if(!window.Chart) return;
            this.renderTrend();
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
                        borderColor: '#A3D9A5', 
                        borderWidth: 2,
                        backgroundColor: 'rgba(163,217,165,0.02)', 
                        pointBackgroundColor: colors, 
                        pointBorderColor: '#121c17', 
                        pointBorderWidth: 1,
                        pointRadius: 3, 
                        pointHoverRadius: 5,
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
                            grid: { borderDash: [4, 4], color: 'rgba(255,255,255,0.03)' },
                            ticks: { 
                                color: 'rgba(255,255,255,0.3)',
                                font: { family: 'Satoshi, Inter, sans-serif', size: 8 } 
                            }
                        },
                        x: { 
                            grid: { display: false },
                            ticks: { 
                                color: 'rgba(255,255,255,0.3)',
                                font: { family: 'Satoshi, Inter, sans-serif', size: 8 } 
                            }
                        }
                    }
                }
            });
        }
    }));
});
</script>
