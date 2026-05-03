<div class="p-6 max-w-5xl mx-auto space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold" style="color: var(--eco-primary)">Kalkulator Jejak Karbon</h1>
        <p class="text-sm mt-1" style="color: var(--eco-text-secondary)">
            Estimasi emisi CO₂ per aktivitas · Metodologi IPCC &amp; DEFRA
        </p>
    </div>

    {{-- Toast --}}
    @if($saved)
        <div class="eco-toast" x-data x-init="setTimeout(() => $wire.set('saved', false), 3000)">
            ✅ Transaksi berhasil disimpan!
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-6">

        {{-- ── Left: Input Form ──────────────────────────────────────────── --}}
        <div class="flex-1 space-y-4">

            {{-- Tab Buttons --}}
            <div class="eco-input-card" style="padding: var(--eco-space-md);">
                <p class="eco-number-label mb-3">📌 Kategori Sumber Emisi</p>
                <div class="flex flex-wrap gap-2">
                    @php
                        $tabs = [
                            'bahan_bakar' => ['⛽', 'Bahan Bakar'],
                            'elektronik'  => ['⚡', 'Elektronik'],
                            'penerbangan' => ['✈️', 'Penerbangan'],
                            'makanan'     => ['🍜', 'Makanan'],
                            'sampah'      => ['🗑️', 'Sampah'],
                            'kendaraan'   => ['🚗', 'Kendaraan'],
                        ];
                    @endphp
                    @foreach($tabs as $slug => [$icon, $label])
                        <button
                            wire:click="setTab('{{ $slug }}')"
                            class="eco-btn text-sm px-4 py-2 {{ $activeTab === $slug ? 'eco-btn-primary' : 'eco-btn-secondary' }}"
                        >{{ $icon }} {{ $label }}</button>
                    @endforeach
                </div>
            </div>

            {{-- Tab Content --}}
            <div class="eco-input-card space-y-4">

                {{-- ① Bahan Bakar --}}
                @if($activeTab === 'bahan_bakar')
                    <div class="eco-formula-note">
                        <strong>Rumus:</strong> Jumlah Liter × Faktor Emisi (kg CO₂/Liter)<br>
                        <em>Sumber: IPCC 2006 Vol.2 Energy, disesuaikan bahan bakar Indonesia</em>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="eco-field-label">Jenis Bahan Bakar</label>
                            <select wire:model.live="bb_ef_id" class="eco-input-field">
                                @foreach($efByCategory['bahan_bakar'] ?? [] as $ef)
                                    <option value="{{ $ef['id'] }}">
                                        {{ $ef['name'] }} ({{ $ef['factor_value'] }} kg CO₂/L)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="eco-field-label">Jumlah (Liter)</label>
                            <input type="number" wire:model.live="bb_liter" min="0" step="0.1"
                                placeholder="0" class="eco-input-field" />
                        </div>
                    </div>
                @endif

                {{-- ② Elektronik --}}
                @if($activeTab === 'elektronik')
                    <div class="eco-formula-note">
                        <strong>Rumus:</strong> Unit × Jam × (Watt/1000) × 0.87 kg CO₂/kWh<br>
                        <em>Sumber: Faktor Emisi PLN Grid Jawa-Bali-Sumatera (ESDM RI)</em>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="eco-field-label">Perangkat</label>
                            <select wire:model.live="el_ef_id" class="eco-input-field">
                                @foreach($efByCategory['elektronik'] ?? [] as $ef)
                                    @php $w = $ef['metadata']['watt'] ?? 0; @endphp
                                    <option value="{{ $ef['id'] }}">
                                        {{ $ef['name'] }} ({{ $w }}W)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="eco-field-label">Jumlah Unit</label>
                            <input type="number" wire:model.live="el_unit" min="1" step="1"
                                placeholder="1" class="eco-input-field" />
                        </div>
                        <div>
                            <label class="eco-field-label">Jam Digunakan (sesi ini)</label>
                            <input type="number" wire:model.live="el_jam" min="0" step="0.5"
                                placeholder="0" class="eco-input-field" />
                        </div>
                    </div>
                @endif

                {{-- ③ Penerbangan --}}
                @if($activeTab === 'penerbangan')
                    <div class="eco-formula-note">
                        <strong>Rumus:</strong> Frekuensi × Jarak (km) × EF Kelas × (1 atau 2 arah)<br>
                        <em>Sumber: DEFRA UK — termasuk faktor Radiative Forcing (RFI)</em>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="eco-field-label">Kelas Penerbangan</label>
                            <select wire:model.live="fl_ef_id" class="eco-input-field">
                                @foreach($efByCategory['penerbangan'] ?? [] as $ef)
                                    <option value="{{ $ef['id'] }}">
                                        {{ $ef['name'] }} ({{ $ef['factor_value'] }} kg CO₂/km)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="eco-field-label">Jenis Perjalanan</label>
                            <select wire:model.live="fl_arah" class="eco-input-field">
                                <option value="1">Satu Arah (One Way)</option>
                                <option value="2">Pulang Pergi (Return)</option>
                            </select>
                        </div>
                        <div>
                            <label class="eco-field-label">Jumlah Penerbangan</label>
                            <input type="number" wire:model.live="fl_freq" min="1" step="1"
                                placeholder="1" class="eco-input-field" />
                        </div>
                        <div>
                            <label class="eco-field-label">Jarak Per Trip (km)</label>
                            <input type="number" wire:model.live="fl_km" min="0" step="10"
                                placeholder="cth: 1000" class="eco-input-field" />
                        </div>
                    </div>
                @endif

                {{-- ④ Makanan --}}
                @if($activeTab === 'makanan')
                    <div class="eco-formula-note">
                        <strong>Rumus:</strong> (Gram / 1000) × EF Makanan (kg CO₂e/kg)<br>
                        <em>Sumber: Poore &amp; Nemecek (2018), Science — Life Cycle Assessment</em>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="eco-field-label">Jenis Makanan</label>
                            <select wire:model.live="mk_ef_id" class="eco-input-field">
                                @foreach($efByCategory['makanan'] ?? [] as $ef)
                                    <option value="{{ $ef['id'] }}">
                                        {{ $ef['name'] }} ({{ $ef['factor_value'] }} kg CO₂e/kg)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="eco-field-label">Jumlah (gram)</label>
                            <input type="number" wire:model.live="mk_gram" min="0" step="10"
                                placeholder="cth: 200" class="eco-input-field" />
                        </div>
                    </div>
                @endif

                {{-- ⑤ Sampah --}}
                @if($activeTab === 'sampah')
                    <div class="eco-formula-note">
                        <strong>Rumus:</strong> Kg Sampah × Faktor Emisi (kg CO₂e/kg)<br>
                        <em>Sumber: IPCC Waste Model — asumsi TPA tanpa gas recovery</em>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="eco-field-label">Jenis Sampah</label>
                            <select wire:model.live="sp_ef_id" class="eco-input-field">
                                @foreach($efByCategory['sampah'] ?? [] as $ef)
                                    <option value="{{ $ef['id'] }}">
                                        {{ $ef['name'] }} ({{ $ef['factor_value'] }} kg CO₂e/kg)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="eco-field-label">Jumlah (kg)</label>
                            <input type="number" wire:model.live="sp_kg" min="0" step="0.1"
                                placeholder="cth: 0.5" class="eco-input-field" />
                        </div>
                    </div>
                @endif

                {{-- ⑥ Kendaraan --}}
                @if($activeTab === 'kendaraan')
                    @php
                        $kdMeta = collect($efByCategory['kendaraan'] ?? [])
                            ->firstWhere('id', $kd_ef_id);
                        $kdType = $kdMeta['metadata']['type'] ?? 'bbm';
                    @endphp
                    <div class="eco-formula-note">
                        @if($kdType === 'bbm')
                            <strong>Rumus BBM:</strong> (Km / Km/L) × EF (kg CO₂/liter) / Penumpang
                        @elseif($kdType === 'ev')
                            <strong>Rumus Listrik:</strong> Km × kWh/km × EF PLN / Penumpang
                        @else
                            <strong>Rumus Publik:</strong> Km × EF Per Penumpang
                        @endif
                        <br><em>Sumber: IPCC 2006 Vol.2 / ESDM RI / IEA 2022</em>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="eco-field-label">Jenis Kendaraan</label>
                            <select wire:model.live="kd_ef_id" class="eco-input-field">
                                @foreach($efByCategory['kendaraan'] ?? [] as $ef)
                                    <option value="{{ $ef['id'] }}">{{ $ef['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="eco-field-label">Jarak Ditempuh (km)</label>
                            <input type="number" wire:model.live="kd_km" min="0" step="0.5"
                                placeholder="cth: 15" class="eco-input-field" />
                        </div>
                        @if($kdType !== 'public')
                            <div>
                                <label class="eco-field-label">
                                    {{ $kdType === 'ev' ? 'Konsumsi (kWh/km)' : 'Efisiensi (Km/Liter)' }}
                                </label>
                                <input type="number" wire:model.live="kd_eff" min="0.01" step="0.1"
                                    placeholder="{{ $kdType === 'ev' ? ($kdMeta['metadata']['default_kwh_km'] ?? '0.15') : '12' }}"
                                    class="eco-input-field" />
                            </div>
                            <div>
                                <label class="eco-field-label">Jumlah Penumpang</label>
                                <input type="number" wire:model.live="kd_pax" min="1" step="1"
                                    value="1" class="eco-input-field" />
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Deskripsi opsional --}}
                <div>
                    <label class="eco-field-label">Deskripsi (opsional)</label>
                    <input type="text" wire:model.live="description"
                        placeholder="cth: Isi bensin Pertamax pagi hari"
                        class="eco-input-field" />
                </div>

                @if($errorMsg)
                    <p class="text-sm font-medium" style="color: var(--eco-danger)">⚠️ {{ $errorMsg }}</p>
                @endif

                {{-- Save --}}
                <button
                    type="button"
                    wire:click="saveTransaction"
                    wire:loading.attr="disabled"
                    class="eco-btn eco-btn-primary w-full"
                    style="padding: 14px;"
                    @if($previewCo2e <= 0) disabled @endif
                >
                    <span wire:loading.remove>💾 Simpan ke Riwayat</span>
                    <span wire:loading>Menyimpan...</span>
                </button>

            </div>
        </div>

        {{-- ── Right: Live Result Panel ─────────────────────────────────── --}}
        <div class="w-full lg:w-72 shrink-0">
            <div class="bento-card sticky top-6 space-y-4">
                <p class="text-center font-bold text-lg" style="color: var(--eco-text-primary)">
                    Estimasi Emisi
                </p>

                <div class="text-center">
                    <span class="eco-number" style="font-size: 2.5rem; color: var(--eco-primary);"
                          wire:loading.class="opacity-40">
                        {{ number_format($previewCo2e, 3) }}
                    </span>
                    <p class="text-sm mt-1" style="color: var(--eco-text-secondary)">kg CO₂e</p>
                </div>

                {{-- Level badge --}}
                @php
                    [$lvlLabel, $lvlColor] = match(true) {
                        $previewCo2e <= 0    => ['—', 'default'],
                        $previewCo2e <= 0.5  => ['Rendah 🌿', 'success'],
                        $previewCo2e <= 2.0  => ['Sedang ⚠️', 'warning'],
                        default              => ['Tinggi 🔴', 'danger'],
                    };
                @endphp
                @if($previewCo2e > 0)
                    <div class="text-center">
                        <span class="eco-badge eco-badge--{{ $lvlColor }} text-sm px-4 py-1">{{ $lvlLabel }}</span>
                    </div>

                    {{-- Equivalence context --}}
                    <div class="eco-formula-note text-xs">
                        💡
                        @if($previewCo2e < 0.1)
                            Setara menanam pohon ~{{ round($previewCo2e * 50, 1) }} hari
                        @elseif($previewCo2e < 5)
                            Setara berkendara motor ~{{ round($previewCo2e / 0.083, 0) }} km
                        @else
                            Setara emisi penerbangan ~{{ round($previewCo2e / 0.15 / 500, 1) }} rute pendek
                        @endif
                    </div>
                @else
                    <p class="text-center text-sm" style="color: var(--eco-text-secondary)">
                        Isi form untuk lihat estimasi
                    </p>
                @endif

                {{-- Breakdown per tab --}}
                <div class="border-t pt-3" style="border-color: var(--eco-overlay);">
                    <p class="text-xs font-semibold mb-2" style="color: var(--eco-text-secondary)">
                        Formula aktif:
                    </p>
                    <p class="text-xs" style="color: var(--eco-text-secondary)">
                        @switch($activeTab)
                            @case('bahan_bakar') {{ $bb_liter }} L × EF = {{ $previewCo2e }} kg @break
                            @case('elektronik')  {{ $el_unit }}×{{ $el_jam }}jam × (watt/1000) × 0.87 @break
                            @case('penerbangan') {{ $fl_freq }}× {{ $fl_km }}km × EF × {{ $fl_arah }} @break
                            @case('makanan')     {{ $mk_gram }}g / 1000 × EF @break
                            @case('sampah')      {{ $sp_kg }} kg × EF @break
                            @case('kendaraan')   {{ $kd_km }}km / eff × EF / {{ $kd_pax }} pax @break
                        @endswitch
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
.eco-formula-note {
    background: rgba(163, 217, 165, 0.08);
    border: 1px solid var(--eco-secondary, #A3D9A5);
    border-radius: var(--eco-radius-sm, 8px);
    padding: 10px 14px;
    font-size: 0.8rem;
    color: var(--eco-text-secondary);
    line-height: 1.5;
}
.eco-field-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 4px;
    color: var(--eco-text-secondary);
}
</style>
