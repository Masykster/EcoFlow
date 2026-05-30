<div class="p-6 max-w-6xl mx-auto">

    <div>
        <h1 class="text-3xl font-bold text-gray-900">Kalkulator Jejak Karbon</h1>
        <p class="text-sm text-gray-500 mt-2">Estimasi emisi CO₂ per aktivitas · Metodologi IPCC & DEFRA</p>
    </div>

    @if($saved)
        <div class="mt-4 p-4 bg-green-50 text-green-700 rounded-[20px] border border-green-200 flex items-center gap-2" x-data x-init="setTimeout(() => $wire.set('saved', false), 3000)">
            <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Transaksi berhasil disimpan ke riwayat!</span>
        </div>
    @endif

    <div class="mt-8 flex flex-col lg:flex-row gap-8 items-start">

        {{-- ── Left: Categories & Form ───────────────────────────────────────── --}}
        <div class="flex-1 w-full space-y-6">

            {{-- Categories Grid --}}
            @php
                $tabConfig = [
                    'bahan_bakar' => ['title' => 'Fuel', 'desc' => 'Emisi gas buang bahan bakar.', 'img' => 'icon_fuel.png', 'color' => 'bg-[#2D5F50] text-white', 'border' => 'border-[#2D5F50]'],
                    'elektronik'  => ['title' => 'Electronics', 'desc' => 'Emisi listrik perangkat.', 'img' => 'icon_electronics.png', 'color' => 'bg-[#2D5F50] text-white', 'border' => 'border-[#2D5F50]'],
                    'penerbangan' => ['title' => 'Aviation', 'desc' => 'Penerbangan jarak jauh/dekat.', 'img' => 'icon_aviation.png', 'color' => 'bg-[#2D5F50] text-white', 'border' => 'border-[#2D5F50]'],
                    'makanan'     => ['title' => 'Food', 'desc' => 'Emisi produksi & konsumsi.', 'img' => 'icon_food.png', 'color' => 'bg-[#2D5F50] text-white', 'border' => 'border-[#2D5F50]'],
                    'sampah'      => ['title' => 'Waste', 'desc' => 'Emisi pengolahan limbah.', 'img' => 'icon_waste.png', 'color' => 'bg-[#2D5F50] text-white', 'border' => 'border-[#2D5F50]'],
                    'kendaraan'   => ['title' => 'Vehicle', 'desc' => 'Emisi perjalanan darat.', 'img' => 'icon_vehicle.png', 'color' => 'bg-[#2D5F50] text-white', 'border' => 'border-[#2D5F50]'],
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($tabConfig as $slug => $config)
                    @php 
                        $isActive = $activeTab === $slug;
                        $bgClass = $isActive ? $config['color'] : 'bg-white text-gray-800';
                        $borderClass = $isActive ? 'border-2 '.$config['border'] : 'border border-gray-200 hover:border-gray-300';
                    @endphp
                    <div wire:click="setTab('{{ $slug }}')" 
                         wire:key="tab-{{ $slug }}"
                         class="cursor-pointer rounded-[20px] p-4 transition-all shadow-sm flex flex-col justify-between relative overflow-hidden active:scale-[0.98] active:translate-y-0.5 {{ $bgClass }} {{ $borderClass }}"
                         style="min-height: 120px;">
                        
                        @if($isActive)
                            <div class="absolute top-3 right-3 bg-white text-[#2D5F50] rounded-full p-0.5">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            </div>
                        @endif

                        <div class="z-10 w-2/3">
                            <h3 class="font-bold text-lg mb-1">{{ $config['title'] }}</h3>
                            <p class="text-xs {{ $isActive && str_contains($config['color'], 'text-white') ? 'text-gray-200' : 'text-gray-500' }} leading-snug">{{ $config['desc'] }}</p>
                        </div>
                        <img src="/images/{{ $config['img'] }}" class="absolute bottom-2 right-2 w-14 h-14 object-contain drop-shadow-md" alt="{{ $config['title'] }}">
                    </div>
                @endforeach
            </div>

            {{-- Form Area --}}
            <div class="bg-white rounded-[20px] p-6 shadow-sm border border-gray-100">
                
                {{-- Dynamic Note --}}
                <div class="bg-[#EAF3EB] border border-[#A3D9A5] rounded-[20px] p-4 mb-6 flex gap-3">
                    <svg class="w-5 h-5 text-[#2D5F50] shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-sm text-[#2D5F50]">
                        @if($activeTab === 'bahan_bakar')
                            <strong>Rumus:</strong> Jumlah Liter × Faktor Emisi (kg CO₂/Liter)<br>
                            <span class="text-xs opacity-80">Sumber: IPCC 2006 Vol.2 Energy, disesuaikan bahan bakar Indonesia</span>
                        @elseif($activeTab === 'elektronik')
                            <strong>Rumus:</strong> Unit × Jam × (Watt/1000) × 0.87 kg CO₂/kWh<br>
                            <span class="text-xs opacity-80">Sumber: Faktor Emisi PLN Grid Jawa-Bali-Sumatera (ESDM RI)</span>
                        @elseif($activeTab === 'penerbangan')
                            <strong>Rumus:</strong> Frekuensi × Jarak (km) × EF Kelas × (1 atau 2 arah)<br>
                            <span class="text-xs opacity-80">Sumber: DEFRA UK — termasuk faktor Radiative Forcing (RFI)</span>
                        @elseif($activeTab === 'makanan')
                            <strong>Rumus:</strong> (Gram / 1000) × EF Makanan (kg CO₂e/kg)<br>
                            <span class="text-xs opacity-80">Sumber: Poore & Nemecek (2018), Science</span>
                        @elseif($activeTab === 'sampah')
                            <strong>Rumus:</strong> Kg Sampah × Faktor Emisi (kg CO₂e/kg)<br>
                            <span class="text-xs opacity-80">Sumber: IPCC Waste Model</span>
                        @elseif($activeTab === 'kendaraan')
                            <strong>Rumus:</strong> Bervariasi berdasarkan jenis BBM/EV/Publik<br>
                            <span class="text-xs opacity-80">Sumber: IPCC 2006 / ESDM RI / IEA 2022</span>
                        @endif
                    </div>
                </div>

                {{-- Form Fields (Dynamic based on tab) --}}
                <div class="space-y-6">
                    
                    @if($activeTab === 'bahan_bakar')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Bahan Bakar</label>
                                <select wire:model.live="bb_ef_id" class="w-full rounded-[10px] border-gray-200 bg-gray-50 focus:bg-white focus:ring-[#A3D9A5] focus:border-[#A3D9A5]">
                                    @foreach($efByCategory['bahan_bakar'] ?? [] as $ef)
                                        <option value="{{ $ef['id'] }}">
                                            {{ $ef['name'] }} at {{ $ef['factor_value'] }} kg CO₂/{{ $ef['unit'] ?? 'L' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah (Liter)</label>
                                <input type="number" wire:model.live="bb_liter" min="0" step="0.1" class="w-full rounded-[10px] border-gray-200 bg-gray-50 focus:bg-white focus:ring-[#A3D9A5] focus:border-[#A3D9A5]" />
                            </div>
                        </div>
                    @endif

                    @if($activeTab === 'elektronik')
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Perangkat</label>
                                <select wire:model.live="el_ef_id" class="w-full rounded-[10px] border-gray-200 bg-gray-50 focus:bg-white focus:ring-[#A3D9A5] focus:border-[#A3D9A5]">
                                    @foreach($efByCategory['elektronik'] ?? [] as $ef)
                                        <option value="{{ $ef['id'] }}">{{ $ef['name'] }} ({{ $ef['metadata']['watt'] ?? 0 }}W)</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Unit</label>
                                <input type="number" wire:model.live="el_unit" min="1" step="1" class="w-full rounded-[10px] border-gray-200 bg-gray-50 focus:bg-white focus:ring-[#A3D9A5] focus:border-[#A3D9A5]" />
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Jam Penggunaan</label>
                                <input type="number" wire:model.live="el_jam" min="0" step="0.5" class="w-full rounded-[10px] border-gray-200 bg-gray-50 focus:bg-white focus:ring-[#A3D9A5] focus:border-[#A3D9A5]" />
                            </div>
                        </div>
                    @endif

                    @if($activeTab === 'penerbangan')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Kelas Penerbangan</label>
                                <select wire:model.live="fl_ef_id" class="w-full rounded-[10px] border-gray-200 bg-gray-50 focus:bg-white focus:ring-[#A3D9A5] focus:border-[#A3D9A5]">
                                    @foreach($efByCategory['penerbangan'] ?? [] as $ef)
                                        <option value="{{ $ef['id'] }}">{{ $ef['name'] }} at {{ $ef['factor_value'] }} kg/km</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Perjalanan</label>
                                <select wire:model.live="fl_arah" class="w-full rounded-[10px] border-gray-200 bg-gray-50 focus:bg-white focus:ring-[#A3D9A5] focus:border-[#A3D9A5]">
                                    <option value="1">Satu Arah (One Way)</option>
                                    <option value="2">Pulang Pergi (Return)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Penerbangan</label>
                                <input type="number" wire:model.live="fl_freq" min="1" step="1" class="w-full rounded-[10px] border-gray-200 bg-gray-50 focus:bg-white focus:ring-[#A3D9A5] focus:border-[#A3D9A5]" />
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Jarak (km)</label>
                                <input type="number" wire:model.live="fl_km" min="0" step="10" class="w-full rounded-[10px] border-gray-200 bg-gray-50 focus:bg-white focus:ring-[#A3D9A5] focus:border-[#A3D9A5]" />
                            </div>
                        </div>
                    @endif

                    @if($activeTab === 'makanan')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Makanan</label>
                                <select wire:model.live="mk_ef_id" class="w-full rounded-[10px] border-gray-200 bg-gray-50 focus:bg-white focus:ring-[#A3D9A5] focus:border-[#A3D9A5]">
                                    @foreach($efByCategory['makanan'] ?? [] as $ef)
                                        <option value="{{ $ef['id'] }}">{{ $ef['name'] }} at {{ $ef['factor_value'] }} kg/kg</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah (gram)</label>
                                <input type="number" wire:model.live="mk_gram" min="0" step="10" class="w-full rounded-[10px] border-gray-200 bg-gray-50 focus:bg-white focus:ring-[#A3D9A5] focus:border-[#A3D9A5]" />
                            </div>
                        </div>
                    @endif

                    @if($activeTab === 'sampah')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Sampah</label>
                                <select wire:model.live="sp_ef_id" class="w-full rounded-[10px] border-gray-200 bg-gray-50 focus:bg-white focus:ring-[#A3D9A5] focus:border-[#A3D9A5]">
                                    @foreach($efByCategory['sampah'] ?? [] as $ef)
                                        <option value="{{ $ef['id'] }}">{{ $ef['name'] }} at {{ $ef['factor_value'] }} kg/kg</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah (kg)</label>
                                <input type="number" wire:model.live="sp_kg" min="0" step="0.1" class="w-full rounded-[10px] border-gray-200 bg-gray-50 focus:bg-white focus:ring-[#A3D9A5] focus:border-[#A3D9A5]" />
                            </div>
                        </div>
                    @endif

                    @if($activeTab === 'kendaraan')
                        @php
                            $kdMeta = collect($efByCategory['kendaraan'] ?? [])->firstWhere('id', $kd_ef_id);
                            $kdType = $kdMeta['metadata']['type'] ?? 'bbm';
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Kendaraan</label>
                                <select wire:model.live="kd_ef_id" class="w-full rounded-[10px] border-gray-200 bg-gray-50 focus:bg-white focus:ring-[#A3D9A5] focus:border-[#A3D9A5]">
                                    @foreach($efByCategory['kendaraan'] ?? [] as $ef)
                                        <option value="{{ $ef['id'] }}">{{ $ef['name'] }} at {{ $ef['factor_value'] }} kg</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Jarak Tempuh (km)</label>
                                <input type="number" wire:model.live="kd_km" min="0" step="0.5" class="w-full rounded-[10px] border-gray-200 bg-gray-50 focus:bg-white focus:ring-[#A3D9A5] focus:border-[#A3D9A5]" />
                            </div>
                            @if($kdType !== 'public')
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ $kdType === 'ev' ? 'Konsumsi (kWh/km)' : 'Efisiensi (Km/Liter)' }}</label>
                                    <input type="number" wire:model.live="kd_eff" min="0.01" step="0.1" class="w-full rounded-[10px] border-gray-200 bg-gray-50 focus:bg-white focus:ring-[#A3D9A5] focus:border-[#A3D9A5]" />
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Penumpang</label>
                                    <input type="number" wire:model.live="kd_pax" min="1" step="1" class="w-full rounded-[10px] border-gray-200 bg-gray-50 focus:bg-white focus:ring-[#A3D9A5] focus:border-[#A3D9A5]" />
                                </div>
                            @endif
                        </div>
                    @endif

                    @if(!$isGuestMode)
                    <div class="pt-4">
                        <button wire:click="saveTransaction" wire:loading.attr="disabled"
                                class="w-full bg-[#2D5F50] hover:bg-[#1f4238] active:scale-[0.98] active:translate-y-0.5 text-white font-bold py-4 rounded-[20px] shadow-md transition-all flex items-center justify-center gap-2"
                                @if($previewCo2e <= 0) disabled @endif>
                            <span wire:loading.remove class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                <span>Simpan ke Riwayat</span>
                            </span>
                            <span wire:loading>Menyimpan...</span>
                        </button>
                    </div>

                    @if($errorMsg)
                        <div class="flex items-center justify-center gap-2 text-sm font-medium text-red-500 mt-2">
                            <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>{{ $errorMsg }}</span>
                        </div>
                    @endif
                    @endif

                </div>
            </div>
        </div>

        {{-- ── Right: Summary Panel ────────────────────────────────────────── --}}
        <div class="w-full lg:w-[350px] shrink-0 bg-white rounded-[20px] p-8 shadow-lg border border-gray-100 sticky top-6">
            <h3 class="text-center font-bold text-xl text-gray-800">Ringkasan Estimasi</h3>
            
            <div class="text-center mt-6">
                <span class="text-6xl font-black text-[#2D5F50] block mb-1 leading-none" wire:loading.class="opacity-50 transition-opacity">{{ number_format($previewCo2e, 3) }}</span>
                <p class="text-gray-500 font-medium">kg CO₂e</p>
            </div>
            
            <p class="text-center text-sm text-gray-400 mt-4">Isi form untuk lihat estimasi</p>

            <div class="my-6 border-t border-gray-100"></div>

            {{-- Formula Display --}}
            <div class="mb-6">
                <p class="text-xs font-bold text-gray-400 uppercase mb-2">Formula aktif:</p>
                <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-[20px] border border-gray-100 font-mono text-xs">
                    @switch($activeTab)
                        @case('bahan_bakar') {{ $bb_liter }} L × EF = {{ $previewCo2e }} kg @break
                        @case('elektronik')  {{ $el_unit }} unit × {{ $el_jam }} jam × (watt/1000) × 0.87 = {{ $previewCo2e }} kg @break
                        @case('penerbangan') {{ $fl_freq }} trip × {{ $fl_km }} km × EF × {{ $fl_arah }} = {{ $previewCo2e }} kg @break
                        @case('makanan')     {{ $mk_gram }} g / 1000 × EF = {{ $previewCo2e }} kg @break
                        @case('sampah')      {{ $sp_kg }} kg × EF = {{ $previewCo2e }} kg @break
                        @case('kendaraan')   {{ $kd_km }} km / eff × EF / {{ $kd_pax }} pax = {{ $previewCo2e }} kg @break
                    @endswitch
                </p>
            </div>



            {{-- Feature 3: Realistic Impact Analogies --}}
            <div class="bg-[#F9FAFB] p-4 rounded-[20px] border border-gray-100 space-y-3">
                <p class="text-xs font-bold text-gray-400 uppercase mb-2">Komparasi Dampak</p>
                @php
                    $co2 = $previewCo2e;
                    $trees = round($co2 / 21, 2);        // 1 tree absorbs ~21 kg CO2/year
                    $charges = round($co2 / 0.008, 0);    // ~0.008 kg per full charge
                    $carKm = round($co2 / 0.21, 1);       // ~0.21 kg CO2/km avg car
                    $streaming = round($co2 / 0.036, 1);  // ~0.036 kg per hour streaming
                @endphp
                <div class="flex items-center gap-3 text-sm text-gray-600">
                    <svg class="w-5 h-5 text-[#2D5F50] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M12 3C8.5 7 5.5 11 5.5 15c0 3.5 2.5 6 6.5 6m0-18c3.5 4 6.5 8 6.5 12 0 3.5-2.5 6-6.5 6" />
                    </svg>
                    <p>Setara menanam <strong class="text-[#2D5F50]">{{ $trees }} pohon</strong> selama 1 tahun</p>
                </div>
                <div class="flex items-center gap-3 text-sm text-gray-600">
                    <svg class="w-5 h-5 text-[#2D5F50] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="7" y="2" width="10" height="20" rx="2" ry="2" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 18h2" />
                    </svg>
                    <p>Sama dengan <strong class="text-[#2D5F50]">{{ $charges }}x</strong> isi daya smartphone</p>
                </div>
                <div class="flex items-center gap-3 text-sm text-gray-600">
                    <svg class="w-5 h-5 text-[#2D5F50] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10M21 16V10a2 2 0 00-2-2h-5M3 13h18" />
                    </svg>
                    <p>Setara <strong class="text-[#2D5F50]">{{ $carKm }} km</strong> perjalanan mobil</p>
                </div>
                <div class="flex items-center gap-3 text-sm text-gray-600">
                    <svg class="w-5 h-5 text-[#2D5F50] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 21h8M12 17v4" />
                    </svg>
                    <p>Sama dengan <strong class="text-[#2D5F50]">{{ $streaming }} jam</strong> streaming video</p>
                </div>
                <p class="text-xs text-gray-400 pt-2 border-t border-gray-200 mt-2">Rata-rata emisi harian global: 13 kg CO₂e/orang.</p>
            </div>
        </div>

    </div>

    {{-- Feature 3: Product Duel Section --}}
    <div id="product-duel" class="mt-10" x-data="{
        duels: [
            { a: { name: 'Kemeja Katun Baru', icon: 'shirt', co2: 10.0 }, b: { name: 'Kemeja Thrifting', icon: 'recycle', co2: 0.5 } },
            { a: { name: 'Daging Sapi 1kg', icon: 'meat', co2: 27.0 }, b: { name: 'Ayam 1kg', icon: 'chicken', co2: 6.9 } },
            { a: { name: 'Mobil Pribadi 10km', icon: 'car', co2: 2.1 }, b: { name: 'KRL/MRT 10km', icon: 'train', co2: 0.14 } },
            { a: { name: 'Botol Plastik Baru', icon: 'bottle', co2: 0.08 }, b: { name: 'Tumbler Reusable', icon: 'tumbler', co2: 0.002 } },
        ],
        selected: 0
    }">
        <h2 class='text-xl font-bold text-gray-800 mb-4 flex items-center gap-2'>
            <svg class="w-6 h-6 text-[#2D5F50] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
            </svg>
            <span>Product Duel — Bandingkan Sebelum Memilih</span>
        </h2>
        <p class='text-sm text-gray-500 mb-6'>Bandingkan emisi karbon dari dua pilihan sehari-hari.</p>

        {{-- Duel Selector --}}
        <div class='flex flex-wrap gap-2 mb-6'>
            <template x-for='(duel, i) in duels' :key='i'>
                <button @click='selected = i'
                        class='px-4 py-2 rounded-[20px] text-sm font-bold border transition-all active:scale-[0.98] active:translate-y-0.5'
                        :class='selected === i ? "bg-[#2D5F50] text-white border-[#2D5F50]" : "bg-white text-gray-600 border-gray-200 hover:bg-gray-50"'
                        x-text='duel.a.name + " vs " + duel.b.name'></button>
            </template>
        </div>

        {{-- Duel Cards --}}
        <div class='grid grid-cols-1 md:grid-cols-2 gap-6'>
            {{-- Option A --}}
            <div class='bg-white rounded-[20px] p-6 border border-gray-100 shadow-sm text-center relative overflow-hidden flex flex-col items-center justify-between'>
                <div class='absolute top-0 left-0 w-full h-1' :class='duels[selected].a.co2 > duels[selected].b.co2 ? "bg-red-400" : "bg-green-400"'></div>
                
                {{-- SVG Icon Container A --}}
                <div class="mb-3 w-16 h-16 flex items-center justify-center text-[#2D5F50]">
                    <template x-if="duels[selected].a.icon === 'shirt'">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 5.5L7 3l5 3 5-3 3 2.5V10h-2v11H6V10H4V5.5z M12 6v15" />
                        </svg>
                    </template>
                    <template x-if="duels[selected].a.icon === 'meat'">
                        <svg class="w-16 h-16 text-red-700" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 14.5c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5.67 1.5 1.5 1.5 1.5-.67 1.5-1.5zm1.5-5.5c-.83 0-1.5.67-1.5 1.5s.67 1.5 1.5 1.5 1.5-.67 1.5-1.5-.67-1.5-1.5-1.5z" />
                        </svg>
                    </template>
                    <template x-if="duels[selected].a.icon === 'car'">
                        <svg class="w-16 h-16 text-red-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 18a2 2 0 100-4 2 2 0 000 4z M19 18a2 2 0 100-4 2 2 0 000 4z M2 10h20 M4 10l2-5h12l2 5 M3 10v5h18v-5" />
                        </svg>
                    </template>
                    <template x-if="duels[selected].a.icon === 'bottle'">
                        <svg class="w-16 h-16 text-blue-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 2h4v2h-4V2z M8 8h8v12a2 2 0 01-2 2H10a2 2 0 01-2-2V8z M8 12h8" />
                        </svg>
                    </template>
                </div>

                <h3 class='font-bold text-lg text-gray-800 mb-2' x-text='duels[selected].a.name'></h3>
                <span class='text-3xl font-black' :class='duels[selected].a.co2 > duels[selected].b.co2 ? "text-red-500" : "text-green-600"' x-text='duels[selected].a.co2'></span>
                <p class='text-sm text-gray-500'>kg CO₂e</p>
            </div>
            
            {{-- Option B --}}
            <div class='bg-white rounded-[20px] p-6 border border-gray-100 shadow-sm text-center relative overflow-hidden flex flex-col items-center justify-between'>
                <div class='absolute top-0 left-0 w-full h-1' :class='duels[selected].b.co2 > duels[selected].a.co2 ? "bg-red-400" : "bg-green-400"'></div>
                
                {{-- SVG Icon Container B --}}
                <div class="mb-3 w-16 h-16 flex items-center justify-center text-[#2D5F50]">
                    <template x-if="duels[selected].b.icon === 'recycle'">
                        <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h5M4 4v5M4 4l7 7 M20 20h-5M20 20v-5M20 20l-7-7 M20 4v5M20 4h-5M20 4l-7 7 M4 20v-5M4 20h5M4 20l7-7" />
                        </svg>
                    </template>
                    <template x-if="duels[selected].b.icon === 'chicken'">
                        <svg class="w-16 h-16 text-yellow-700" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 2a4 4 0 00-4 4v3H6a2 2 0 00-2 2v5a4 4 0 004 4h8a4 4 0 004-4v-5a2 2 0 00-2-2h-2V6a4 4 0 00-4-4z" />
                        </svg>
                    </template>
                    <template x-if="duels[selected].b.icon === 'train'">
                        <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <rect x="4" y="3" width="16" height="15" rx="2" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8M8 11h8M6 18l-2 3M18 18l2 3M12 18v-3" />
                        </svg>
                    </template>
                    <template x-if="duels[selected].b.icon === 'tumbler'">
                        <svg class="w-16 h-16 text-green-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 3h8v2H8V3z M6 7h12l-2 14H8L6 7z M12 7v14" />
                        </svg>
                    </template>
                </div>

                <h3 class='font-bold text-lg text-gray-800 mb-2' x-text='duels[selected].b.name'></h3>
                <span class='text-3xl font-black' :class='duels[selected].b.co2 > duels[selected].a.co2 ? "text-red-500" : "text-green-600"' x-text='duels[selected].b.co2'></span>
                <p class='text-sm text-gray-500'>kg CO₂e</p>
            </div>
        </div>

        {{-- Savings summary --}}
        <div class='mt-4 bg-green-50 border border-green-200 rounded-[20px] p-4 text-center flex items-center justify-center gap-2'>
            <svg class="w-5 h-5 text-green-800 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 113.536 0V21h-2v-4.6z" />
            </svg>
            <p class='text-sm text-green-800 font-medium'>
                Memilih opsi hijau menghemat
                <strong x-text='Math.abs(duels[selected].a.co2 - duels[selected].b.co2).toFixed(2)'></strong> kg CO₂e
                (<strong x-text='(Math.abs(duels[selected].a.co2 - duels[selected].b.co2) / Math.max(duels[selected].a.co2, duels[selected].b.co2) * 100).toFixed(0)'></strong>% lebih rendah)
            </p>
        </div>
    </div>
</div>


