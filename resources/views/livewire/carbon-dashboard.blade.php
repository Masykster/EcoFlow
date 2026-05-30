<div class="flex flex-col gap-4 h-full min-h-0 text-slate-800 dark:text-white" 
     x-data="dashboardCharts()"
     x-on:livewire:update.document="renderCharts()"
     x-on:livewire:navigated.document="renderCharts()"
     x-on:theme-changed.window="renderCharts()">

    {{-- Row 1: Title & Period Switcher --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 shrink-0">
        <h2 class="text-3xl font-black tracking-tight text-slate-800 dark:text-white leading-none">
            Carbon Analytics
        </h2>
        
        <!-- Period Switcher -->
        <div class="flex bg-slate-100 dark:bg-[#1d2722]/80 border border-black/5 dark:border-white/5 p-1 rounded-full shadow-inner"
             x-data="{ currentPeriod: @entangle('period').live }">
            @foreach(['daily' => 'Hari Ini', 'weekly' => 'Minggu Ini', 'monthly' => 'Bulan Ini'] as $key => $label)
                <button
                    @click="currentPeriod = '{{ $key }}'"
                    class="px-5 py-2 rounded-full text-xs font-bold transition-all duration-300 cursor-pointer"
                    :class="currentPeriod === '{{ $key }}' ? 'bg-white dark:bg-[#2b3731] text-[#1E3F35] dark:text-[#A3D9A5] shadow-sm border border-black/5 dark:border-white/5' : 'text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-white'"
                    type="button"
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
                    <circle cx="32" cy="32" r="28" class="stroke-current text-slate-200 dark:text-white/5" stroke-width="4.5" fill="transparent" stroke-dasharray="4, 3" />
                    <circle cx="32" cy="32" r="28" class="stroke-current text-[#1E3F35] dark:text-[#A3D9A5]" stroke-width="4.5" fill="transparent"
                            stroke-dasharray="175" stroke-dashoffset="{{ 175 - (175 * min($budgetPercent, 100) / 100) }}" />
                </svg>
                <div class="text-center">
                    <span class="text-sm font-black text-slate-800 dark:text-white leading-none block">{{ round($budgetPercent) }}%</span>
                    <span class="text-[6px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest leading-none block mt-0.5">Budget</span>
                </div>
            </div>
            <div class="text-left">
                <span class="block text-[9px] font-bold text-gray-500 uppercase tracking-wider leading-none">Anggaran Terpakai</span>
                <span class="text-xs font-black text-slate-800 dark:text-white mt-1 block">{{ $monthlyUsed }} / {{ $monthlyLimit }} kg</span>
            </div>
        </div>

        <!-- Stats Stack -->
        <div class="lg:col-span-8 grid grid-cols-3 gap-4 text-left border-l border-white/5 pl-6">
            <div>
                <span class="block text-[9px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Total Emisi</span>
                <span class="text-xl md:text-2xl font-black text-slate-800 dark:text-white tracking-tight">
                    {{ $totalCo2e }} <span class="text-xs font-bold text-[#1E3F35] dark:text-[#A3D9A5]">kg</span>
                </span>
            </div>
            <div>
                <span class="block text-[9px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1 flex items-center gap-1">
                    Limit Bulanan
                    <button wire:click="openBudgetModal" class="text-[#1E3F35] dark:text-[#A3D9A5] hover:underline cursor-pointer" type="button">
                        <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </button>
                </span>
                <span class="text-xl md:text-2xl font-black text-slate-800 dark:text-white tracking-tight">
                    {{ $monthlyLimit }} <span class="text-xs font-bold text-[#1E3F35] dark:text-[#A3D9A5]">kg</span>
                </span>
            </div>
            <div>
                <span class="block text-[9px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Prediksi AI</span>
                <span class="text-xl md:text-2xl font-black text-slate-800 dark:text-white tracking-tight">
                    {{ $forecastEndMonth > 0 ? $forecastEndMonth : '0.00' }} <span class="text-xs font-bold text-[#1E3F35] dark:text-[#A3D9A5]">kg</span>
                </span>
            </div>
        </div>
    </div>

    {{-- Row 3: Category Pills --}}
    <div class="flex flex-wrap items-center justify-between gap-4 border-t border-white/5 pt-4 shrink-0">
        <div class="flex flex-wrap gap-2 items-center"
             x-data="{ currentCategory: @entangle('activeCategory').live }">
            <button @click="currentCategory = null" 
                    class="px-3.5 py-1.5 rounded-full text-[10px] font-bold transition-all duration-300 cursor-pointer"
                    :class="(currentCategory === null || currentCategory === '') ? 'bg-[#1b2520] border border-[#A3D9A5]/30 text-[#A3D9A5]' : 'bg-slate-100 dark:bg-[#151c19]/60 border border-black/5 dark:border-white/5 text-slate-600 dark:text-gray-400'"
                    type="button">
                Semua Kategori
            </button>
            @foreach(array_slice($byCategory, 0, 5) as $cat)
                <button @click="currentCategory = '{{ $cat['slug'] }}'" 
                        class="px-3.5 py-1.5 rounded-full text-[10px] font-bold transition-all duration-300 cursor-pointer"
                        :class="currentCategory === '{{ $cat['slug'] }}' ? 'bg-emerald-50 dark:bg-[#1b2520] border border-emerald-200 dark:border-[#A3D9A5]/30 text-emerald-800 dark:text-[#A3D9A5]' : 'bg-slate-100 dark:bg-[#151c19]/60 border border-black/5 dark:border-white/5 text-slate-600 dark:text-gray-400'"
                        type="button">
                    {{ $cat['category'] }}
                </button>
            @endforeach
            <a href="{{ route('calculator') }}" wire:navigate 
               class="w-6 h-6 rounded-full bg-slate-100 dark:bg-[#1b2520] border border-black/5 dark:border-white/10 flex items-center justify-center text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-white transition-colors cursor-pointer"
               title="Catat Emisi Baru">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
            </a>
        </div>
    </div>

    {{-- Row 4: Grid Layout (Main Body Content) --}}
    <div class="flex-1 min-h-0 grid grid-cols-12 gap-4">
        <!-- Left Side Widget Container (Spans 8 cols) -->
        <div class="col-span-8 flex flex-col gap-4 h-full min-h-0">
            <!-- Process Milestone Pipeline -->
            <div class="relative flex items-center justify-between w-full px-12 py-2.5 border border-black/5 dark:border-white/5 bg-gray-200/20 dark:bg-[#141d19]/40 rounded-2xl shrink-0">
                <div class="absolute left-16 right-16 top-1/2 h-[1px] bg-black/5 dark:bg-white/10 z-0"></div>
                
                <!-- Node 1 -->
                <div class="relative z-10 flex flex-col items-center gap-1">
                    <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-[#1b2520] border border-black/5 dark:border-white/10 flex items-center justify-center text-emerald-800 dark:text-[#A3D9A5] shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>
                        </svg>
                    </div>
                    <span class="text-[8px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest leading-none">Atur Limit</span>
                </div>

                <!-- Node 2 -->
                <div class="relative z-10 flex flex-col items-center gap-1">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-[#1b2520] border border-black/5 dark:border-white/10 flex items-center justify-center text-slate-800 dark:text-white shadow-md">
                        <div class="flex -space-x-1.5">
                            <div class="w-3.5 h-3.5 rounded-full bg-emerald-700 border border-white dark:border-[#1b2520] flex items-center justify-center text-[6px] font-bold">1</div>
                            <div class="w-3.5 h-3.5 rounded-full bg-teal-700 border border-white dark:border-[#1b2520] flex items-center justify-center text-[6px] font-bold">2</div>
                            <div class="w-3.5 h-3.5 rounded-full bg-[#A3D9A5] text-black border border-white dark:border-[#1b2520] flex items-center justify-center text-[6px] font-bold">+</div>
                        </div>
                    </div>
                    <span class="text-[8px] font-bold text-emerald-700 dark:text-[#A3D9A5] uppercase tracking-widest leading-none">Catat Emisi</span>
                </div>

                <!-- Node 3 -->
                <div class="relative z-10 flex flex-col items-center gap-1">
                    <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-[#1b2520] border border-black/5 dark:border-white/10 flex items-center justify-center text-emerald-800 dark:text-[#A3D9A5] shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3M3.994 8.359a8.97 8.97 0 011.558-2.23"/>
                        </svg>
                    </div>
                    <span class="text-[8px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest leading-none">Aksi Hijau</span>
                </div>
            </div>

            <!-- Three widgets grid (Takes remaining space) -->
            <div class="flex-1 min-h-0 grid grid-cols-3 gap-4">
                <!-- Widget 1: Calendar Grid -->
                <div class="bg-white dark:bg-[#151d19]/40 border border-black/5 dark:border-white/5 rounded-[2rem] p-4 flex flex-col justify-between h-full min-h-0 shadow-sm dark:shadow-none"
                     x-data="{ selectedDay: @entangle('selectedDay').live }">
                    <div>
                        <span class="block text-[9px] font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider mb-2">Tanggal Pelacakan</span>
                        <div class="grid grid-cols-7 gap-x-0.5 gap-y-1 text-center text-[8px]">
                            @for($i = 1; $i <= now()->daysInMonth; $i++)
                                @php 
                                    $isToday = $i == now()->day; 
                                    $hasTrx = in_array($i, $daysWithTransactions);
                                @endphp
                                <button type="button" 
                                        @click="selectedDay = (selectedDay === {{ $i }} ? null : {{ $i }})" 
                                        class="py-1 rounded-md font-bold transition-all hover:scale-105 active:scale-95 cursor-pointer flex flex-col items-center justify-center leading-none min-h-[22px]"
                                        :class="selectedDay === {{ $i }} ? 'bg-emerald-700 text-white shadow-sm' : ({{ $isToday ? 'true' : 'false' }} ? 'bg-[#A3D9A5] text-black shadow-sm' : 'text-slate-600 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-slate-800')">
                                    <span>{{ $i }}</span>
                                    @if($hasTrx)
                                        <span class="w-1 h-1 rounded-full mt-0.5" :class="selectedDay === {{ $i }} ? 'bg-white' : ({{ $isToday ? 'true' : 'false' }} ? 'bg-black' : 'bg-emerald-500')"></span>
                                    @else
                                        <span class="w-1 h-1 mt-0.5 block"></span>
                                    @endif
                                </button>
                            @endfor
                        </div>
                    </div>
                    <div class="border-t border-black/5 dark:border-white/5 pt-2 flex justify-between items-center text-[9px] text-slate-500 dark:text-gray-400 font-semibold">
                        <div>
                            <span class="text-[8px] text-slate-400 dark:text-gray-500 leading-none">Terakhir</span>
                            <span class="text-[10px] font-black text-slate-800 dark:text-white block mt-0.5">
                                {{ $selectedDay ? $selectedDay . ' ' . now()->translatedFormat('M') : 'Hari ini' }}
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="text-[8px] text-slate-400 dark:text-gray-500 leading-none">Aktivitas</span>
                            <span class="text-[10px] font-black text-slate-800 dark:text-white block mt-0.5">{{ count($recentTransactions) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Widget 2: Environment status -->
                <div class="bg-white dark:bg-[#151d19]/40 border border-black/5 dark:border-white/5 rounded-[2rem] p-4 flex flex-col justify-between h-full min-h-0 shadow-sm dark:shadow-none">
                    <div>
                        <span class="block text-[9px] font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider mb-2.5">Level Lingkungan</span>
                        <div class="flex flex-col gap-2">
                            <span class="px-2 py-1.5 rounded-lg text-center text-[10px] font-bold border border-black/5 dark:border-white/5 uppercase tracking-wide bg-emerald-50 dark:bg-[#202b25]/80 text-emerald-800 dark:text-[#A3D9A5] truncate leading-none">
                                @if($budgetStatus === 'safe')
                                    Lestari & Aman
                                @elseif($budgetStatus === 'warning')
                                    Mulai Kritis
                                @else
                                    Bahaya Kritis
                                @endif
                            </span>
                            <span class="px-2 py-1.5 rounded-lg text-center text-[10px] font-bold border border-black/5 dark:border-white/5 uppercase tracking-wide bg-gray-100 dark:bg-[#151c19]/60 text-gray-500 dark:text-gray-400 truncate leading-none">
                                {{ $ecoHomeState }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="border-t border-black/5 dark:border-white/5 pt-2 text-center">
                        <div class="flex items-baseline gap-1 justify-center leading-none">
                            <span class="text-4xl font-black text-slate-800 dark:text-white tracking-tight">{{ $ecoHomeLevel }}</span>
                            <span class="text-[10px] font-bold text-slate-400 dark:text-gray-500">/100</span>
                        </div>
                        <span class="block text-[7px] uppercase tracking-widest text-[#1E3F35] dark:text-[#A3D9A5] mt-1 font-bold">Skor Kelestarian</span>
                    </div>
                </div>

                <!-- Widget 3: Trend Chart integration -->
                <div class="bg-white dark:bg-[#151d19]/40 border border-black/5 dark:border-white/5 rounded-[2rem] p-4 flex flex-col justify-between h-full min-h-0 shadow-sm dark:shadow-none"
                     @click.away="showDropdown = false">
                    <div>
                        <span class="block text-[9px] font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider mb-2">Tren Emisi</span>
                    </div>
                    <div class="flex-1 min-h-0 relative w-full h-32 my-1">
                        <canvas id="trendChart" class="w-full h-full" wire:ignore></canvas>
                    </div>
                    <div class="border-t border-black/5 dark:border-white/5 pt-2 flex justify-between items-center text-[9px] text-slate-500 dark:text-gray-400 font-semibold relative">
                        <div>
                            <span class="text-[8px] text-slate-400 dark:text-gray-500 leading-none" x-text="daysRange + ' Hari Terakhir'">30 Hari Terakhir</span>
                            <span class="text-[10px] font-black text-slate-800 dark:text-white block mt-0.5">Emisi Terpantau</span>
                        </div>
                        <div class="relative">
                            <button @click.stop="showDropdown = !showDropdown" 
                                    class="w-6 h-6 rounded-full bg-slate-100 dark:bg-[#1b2520] border border-black/5 dark:border-white/10 flex items-center justify-center text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-white transition-colors cursor-pointer"
                                    type="button">
                                <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="showDropdown ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="showDropdown" 
                                 x-transition 
                                 class="absolute right-0 bottom-8 w-28 bg-white dark:bg-[#121c17] border border-black/5 dark:border-white/10 rounded-xl shadow-2xl p-1 z-30"
                                 style="display: none;">
                                <button @click="daysRange = 7; showDropdown = false; renderCharts()" 
                                        class="w-full text-left px-3 py-1.5 text-[9px] font-bold rounded-lg transition-colors cursor-pointer text-slate-700 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-[#1b2520]/40"
                                        :class="daysRange === 7 ? 'bg-emerald-50 dark:bg-[#1b2520] text-emerald-800 dark:text-[#A3D9A5]' : ''" type="button">
                                    7 Hari
                                </button>
                                <button @click="daysRange = 30; showDropdown = false; renderCharts()" 
                                        class="w-full text-left px-3 py-1.5 text-[9px] font-bold rounded-lg transition-colors cursor-pointer text-slate-700 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-[#1b2520]/40"
                                        :class="daysRange === 30 ? 'bg-emerald-50 dark:bg-[#1b2520] text-emerald-800 dark:text-[#A3D9A5]' : ''" type="button">
                                    30 Hari
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side Stack Containers (Spans 4 cols) -->
        <div class="col-span-4 flex flex-col gap-4 h-full min-h-0">
            <!-- Card 1: AI Recommendation -->
            <div class="bg-white dark:bg-[#151d19]/40 border border-black/5 dark:border-white/5 rounded-[2.5rem] p-4 flex flex-col justify-between flex-1 min-h-0 shadow-sm dark:shadow-none"
                 x-data="{ toggled: true }">
                <div class="flex justify-between items-start">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-[#A3D9A5]/15 border border-emerald-100 dark:border-[#A3D9A5]/20 flex items-center justify-center text-emerald-700 dark:text-[#A3D9A5] shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <div class="text-left">
                            <span class="block text-xs font-bold text-slate-800 dark:text-white leading-none">Rekomendasi AI</span>
                            <span class="block text-[7px] font-semibold text-slate-400 dark:text-gray-500 uppercase tracking-widest mt-0.5 leading-none">Saran Cerdas</span>
                        </div>
                    </div>
                    <a href="{{ route('calculator', ['activeTab' => !empty($smartRecommendation) ? ($smartRecommendation['icon'] === 'bolt' ? 'elektronik' : ($smartRecommendation['icon'] === 'plane' ? 'penerbangan' : ($smartRecommendation['icon'] === 'utensils' ? 'makanan' : ($smartRecommendation['icon'] === 'fuel' ? 'bahan_bakar' : ($smartRecommendation['icon'] === 'recycle' ? 'sampah' : 'kendaraan'))))) : 'bahan_bakar']) }}" wire:navigate
                       class="w-6 h-6 rounded-full bg-slate-100 dark:bg-[#1b2520] border border-black/5 dark:border-white/10 flex items-center justify-center text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-white transition-colors cursor-pointer"
                       title="Catat Aksi Rekomendasi">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                    </a>
                </div>
                <div class="my-2 text-left flex-1 overflow-y-auto">
                    <div x-show="toggled" x-transition>
                        @if(!empty($smartRecommendation))
                            <p class="text-[10px] font-bold text-emerald-700 dark:text-[#A3D9A5] leading-snug">{{ $smartRecommendation['action'] }}</p>
                            <p class="text-[9px] text-slate-600 dark:text-gray-400 leading-normal mt-1">{{ $smartRecommendation['detail'] }}</p>
                        @else
                            <p class="text-[9px] font-medium text-slate-400 dark:text-gray-500 leading-snug">Saran cerdas akan muncul saat data emisi tercatat.</p>
                        @endif
                    </div>
                    <div x-show="!toggled" x-transition style="display: none;">
                        <p class="text-[9px] font-medium text-slate-400 dark:text-gray-500 leading-snug">Rekomendasi AI dinonaktifkan. Aktifkan toggle di bawah untuk melihat saran.</p>
                    </div>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-black/5 dark:border-white/5 shrink-0">
                    <span class="text-[8px] font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider">
                        Potensi Hemat: <span x-text="toggled ? '{{ $smartRecommendation['saving'] ?? 0 }}' : '0'"></span> kg
                    </span>
                    <button @click="toggled = !toggled" class="relative inline-flex h-4 w-7 shrink-0 cursor-pointer rounded-full border border-transparent transition-colors duration-200 ease-in-out focus:outline-none" :class="toggled ? 'bg-[#A3D9A5]' : 'bg-slate-200 dark:bg-[#2b3731]'" type="button">
                        <span class="pointer-events-none inline-block h-3 w-3 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="toggled ? 'translate-x-3' : 'translate-x-0'"></span>
                    </button>
                </div>
            </div>

            <!-- Card 2: Recent Transactions -->
            <div class="bg-white dark:bg-[#151d19]/40 border border-black/5 dark:border-white/5 rounded-[2.5rem] p-4 flex flex-col justify-between flex-1 min-h-0 shadow-sm dark:shadow-none">
                <div class="flex justify-between items-start shrink-0">
                    <div class="text-left">
                        <span class="block text-xs font-bold text-slate-800 dark:text-white leading-none">
                            Aktivitas Terbaru {{ $selectedDay ? '('.$selectedDay.' '.now()->translatedFormat('M').')' : '' }}
                        </span>
                        <span class="block text-[8px] font-semibold text-slate-400 dark:text-gray-500 uppercase tracking-widest mt-1">
                            @if($selectedDay)
                                Filter Tanggal Aktif · <button @click="$wire.selectedDay = null" class="underline text-emerald-700 dark:text-[#A3D9A5] hover:opacity-80 cursor-pointer font-bold" type="button">Hapus Filter</button>
                            @else
                                Riwayat Aktivitas
                            @endif
                        </span>
                    </div>
                    <a href="{{ route('history') }}" class="text-[9px] font-extrabold text-emerald-700 dark:text-[#A3D9A5] hover:opacity-80 transition-opacity uppercase tracking-wider flex items-center gap-1 leading-none" wire:navigate>
                        Semua
                    </a>
                </div>
                
                <div class="flex-1 min-h-0 overflow-y-auto space-y-2 mt-3 pr-1">
                    @forelse($recentTransactions as $trx)
                        @php
                            $slug = $trx['category']['slug'] ?? 'default';
                            $co2e = $trx['co2e'] ?? 0;
                        @endphp
                        <div class="flex items-center gap-2 p-1.5 bg-gray-50 dark:bg-[#1b2520]/60 rounded-xl border border-black/5 dark:border-white/5">
                            <div class="w-7 h-7 shrink-0 flex items-center justify-center bg-emerald-50 dark:bg-[#A3D9A5]/10 text-emerald-800 dark:text-[#A3D9A5] rounded-lg border border-black/5 dark:border-white/5">
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
                                <p class="text-[9px] font-bold text-slate-800 dark:text-white truncate leading-none">{{ $trx['merchant_name'] }}</p>
                                <span class="text-[7px] font-semibold text-slate-400 dark:text-gray-500 mt-0.5 block leading-none">{{ $trx['category']['name'] ?? 'Uncategorized' }}</span>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-[9px] font-black text-slate-800 dark:text-white">{{ $co2e }} kg</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-slate-400 dark:text-gray-500">
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
        <div class="bg-white dark:bg-[#121c17]/95 border border-black/10 dark:border-white/20 rounded-[24px] p-6 w-full max-w-md shadow-2xl transition-all duration-300">
            <div class="flex items-center gap-2.5 mb-3">
                <svg class="w-5 h-5 text-emerald-700 dark:text-[#A3D9A5] shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>
                </svg>
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Atur Anggaran Karbon</h3>
            </div>
            <p class="text-xs text-slate-600 dark:text-gray-400 mb-4 leading-relaxed font-semibold">Tentukan batas maksimal emisi karbon kamu per bulan (dalam kg CO₂e).</p>
            <input type="number" wire:model="newBudgetLimit" min="1" step="5"
                   class="w-full rounded-xl border-black/10 dark:border-white/10 bg-slate-50 dark:bg-[#0c1410] focus:ring-emerald-500 dark:focus:ring-[#A3D9A5] focus:border-emerald-500 dark:focus:border-[#A3D9A5] text-xl font-black text-center py-3 mb-5 text-slate-800 dark:text-white">
            <div class="flex gap-3">
                <button wire:click="closeBudgetModal" class="flex-1 py-2.5 rounded-xl border border-black/10 dark:border-white/10 text-slate-600 dark:text-gray-400 font-semibold text-xs hover:bg-slate-100 dark:hover:bg-[#1b2520] transition-colors cursor-pointer" type="button">Batal</button>
                <button wire:click="saveBudgetLimit" class="flex-1 py-2.5 rounded-xl bg-emerald-700 dark:bg-[#A3D9A5] text-white dark:text-[#0c1410] font-bold text-xs hover:opacity-90 transition-opacity cursor-pointer" type="button">Simpan</button>
            </div>
        </div>
    </div>
    @endif

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('dashboardCharts', () => ({
        trendChart: null,
        daysRange: 30,
        showDropdown: false,
        init() {
            setTimeout(() => this.renderCharts(), 100);
        },
        renderCharts() {
            if(!window.Chart) return;
            if(!this.$wire) {
                setTimeout(() => this.renderCharts(), 50);
                return;
            }
            this.renderTrend();
        },
        renderTrend() {
            const ctx = document.getElementById('trendChart');
            if(!ctx) return;
            if(this.trendChart) this.trendChart.destroy();
            
            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? 'rgba(255,255,255,0.03)' : 'rgba(0,0,0,0.05)';
            const tickColor = isDark ? 'rgba(255,255,255,0.3)' : 'rgba(0,0,0,0.4)';
            const lineColor = isDark ? '#A3D9A5' : '#10b981';
            const areaBgColor = isDark ? 'rgba(163,217,165,0.02)' : 'rgba(16,185,129,0.03)';
            const pointStrokeColor = isDark ? '#121c17' : '#ffffff';

            const dailyEmissions = this.$wire.dailyEmissions || [];
            const sliceCount = this.daysRange === 7 ? 7 : 30;
            const filteredData = dailyEmissions.slice(-sliceCount);

            const labels = filteredData.map(d => d.date);
            const values = filteredData.map(d => d.total);
            const colors = values.map(v => v > 2.0 ? '#E67E5D' : lineColor);
            
            this.trendChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{ 
                        label: 'Emisi (kg)', 
                        data: values, 
                        borderColor: lineColor, 
                        borderWidth: 2,
                        backgroundColor: areaBgColor, 
                        pointBackgroundColor: colors, 
                        pointBorderColor: pointStrokeColor, 
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
                            grid: { borderDash: [4, 4], color: gridColor },
                            ticks: { 
                                color: tickColor,
                                font: { family: 'Satoshi, Inter, sans-serif', size: 8 } 
                            }
                        },
                        x: { 
                            grid: { display: false },
                            ticks: { 
                                color: tickColor,
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
