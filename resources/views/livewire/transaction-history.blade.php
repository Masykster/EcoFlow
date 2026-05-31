<div class="p-6 space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--eco-primary)">Riwayat Transaksi</h1>
            <p class="text-sm mt-1" style="color: var(--eco-text-secondary)">Kelola dan filter semua riwayat emisi kamu</p>
        </div>
        <div class="flex items-center gap-2">
            <button wire:click="openExportModal"
                class="eco-btn eco-btn-secondary active:scale-[0.98] active:translate-y-0.5 text-sm"
                id="btn-export-open">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Ekspor
            </button>
            <a href="{{ route('calculator') }}" class="eco-btn eco-btn-primary active:scale-[0.98] active:translate-y-0.5">+ Tambah</a>
        </div>
    </div>

    {{-- Export Modal --}}
    @if($showExportModal)
        <div style="position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:50;display:flex;align-items:center;justify-content:center;"
             x-data="{ mode: @entangle('exportMode').live }"
             x-transition>
            <div class="bento-card" style="max-width: 480px; width: 92%;" @click.away="$wire.closeExportModal()">

                {{-- Modal Header --}}
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-lg font-bold" style="color: var(--eco-text-primary)">Ekspor Laporan</h3>
                        <p class="text-xs mt-0.5" style="color: var(--eco-text-secondary)">Pilih periode dan unduh file CSV</p>
                    </div>
                    <button wire:click="closeExportModal"
                        class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-black/5 dark:hover:bg-white/5 transition cursor-pointer"
                        style="color: var(--eco-text-secondary)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Period Preset Pills --}}
                <p class="text-xs font-semibold mb-2 uppercase tracking-wider" style="color: var(--eco-text-secondary)">Periode</p>
                <div class="grid grid-cols-4 gap-2 mb-4">
                    @foreach([
                        'daily'   => 'Hari Ini',
                        'monthly' => 'Bulan Ini',
                        'yearly'  => 'Tahun Ini',
                        'custom'  => 'Custom',
                    ] as $key => $label)
                        <button
                            x-on:click="mode = '{{ $key }}'; $wire.setExportMode('{{ $key }}')"
                            :class="mode === '{{ $key }}'
                                ? 'bg-[#1E3F35] dark:bg-[#A3D9A5] text-white dark:text-[#0c1410] border-[#1E3F35] dark:border-[#A3D9A5]'
                                : 'bg-transparent border-gray-300 dark:border-white/10'"
                            class="text-xs font-bold py-2.5 px-3 rounded-xl border-2 transition-all duration-200 cursor-pointer"
                            style="color: var(--eco-text-primary)"
                            id="export-mode-{{ $key }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>

                {{-- Custom Date Range --}}
                <div x-show="mode === 'custom'" x-transition class="mb-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-semibold mb-1 block" style="color: var(--eco-text-secondary)">Dari</label>
                            <input type="date" wire:model.live="exportFrom" class="eco-input-field text-sm" id="export-from" />
                        </div>
                        <div>
                            <label class="text-xs font-semibold mb-1 block" style="color: var(--eco-text-secondary)">Sampai</label>
                            <input type="date" wire:model.live="exportTo" class="eco-input-field text-sm" id="export-to" />
                        </div>
                    </div>
                </div>

                {{-- Info Note --}}
                <div class="rounded-xl p-3 mb-5" style="background: rgba(163,217,165,0.08); border: 1px solid rgba(163,217,165,0.2);">
                    <p class="text-xs" style="color: var(--eco-text-secondary)">
                        <span class="font-bold" style="color: var(--eco-primary)">ℹ️ Info:</span>
                        File CSV dapat dibuka di Excel, Google Sheets, atau aplikasi spreadsheet lainnya. Filter kategori dan tipe yang aktif akan diterapkan.
                    </p>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-3">
                    <button wire:click="closeExportModal" class="eco-btn eco-btn-secondary flex-1 active:scale-[0.98] active:translate-y-0.5">Batal</button>
                    <button wire:click="exportReport" class="eco-btn eco-btn-primary flex-1 active:scale-[0.98] active:translate-y-0.5" id="btn-download-csv">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download CSV
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Filters --}}
    <div class="bento-card" style="padding: var(--eco-space-md);">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <select wire:model.live="filterCategory" class="eco-input-field">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat['slug'] }}">{{ $cat['name'] }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterType" class="eco-input-field">
                <option value="">Semua Tipe</option>
                <option value="spending">Pengeluaran</option>
                <option value="transport">Transportasi</option>
            </select>
            <input type="date" wire:model.live="filterFrom" class="eco-input-field" placeholder="Dari tanggal" />
            <input type="date" wire:model.live="filterTo" class="eco-input-field" placeholder="Sampai tanggal" />
        </div>
    </div>

    {{-- Delete Confirmation Dialog --}}
    @if($confirmDeleteId)
        <div style="position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:50;display:flex;align-items:center;justify-content:center;">
            <div class="bento-card" style="max-width: 400px; width: 90%;">
                <h3 class="text-lg font-bold mb-2" style="color: var(--eco-text-primary)">Hapus Transaksi?</h3>
                <p class="text-sm mb-6" style="color: var(--eco-text-secondary)">Data yang dihapus tidak dapat dikembalikan.</p>
                <div class="flex gap-3">
                    <button wire:click="cancelDelete" class="eco-btn eco-btn-secondary flex-1 active:scale-[0.98] active:translate-y-0.5">Batal</button>
                    <button wire:click="deleteTransaction" class="eco-btn eco-btn-danger flex-1 active:scale-[0.98] active:translate-y-0.5">Hapus</button>
                </div>
            </div>
        </div>
    @endif

    {{-- Transaction List --}}
    <div class="space-y-2">
        @forelse($transactions as $trx)
            @php
                $slug  = $trx->category?->slug ?? '';
                $co2e  = $trx->co2e ?? 0;
                $level = $co2e <= 0.5 ? 'success' : ($co2e <= 2.0 ? 'warning' : 'danger');
            @endphp
            <div class="eco-history-item">
                <div class="eco-history-icon w-10 h-10 rounded-full flex items-center justify-center" style="background: var(--eco-overlay);">
                    @switch($slug)
                        @case('food')
                            <svg class="w-5 h-5 text-[#2D5F50]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z M8 11h8 M8 14h8" />
                            </svg>
                            @break
                        @case('transport')
                            <svg class="w-5 h-5 text-[#2D5F50]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10M21 16V10a2 2 0 00-2-2h-5M3 13h18" />
                            </svg>
                            @break
                        @case('fashion')
                            <svg class="w-5 h-5 text-[#2D5F50]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 5.5L7 3l5 3 5-3 3 2.5V10h-2v11H6V10H4V5.5z M12 6v15" />
                            </svg>
                            @break
                        @case('electricity')
                            <svg class="w-5 h-5 text-[#2D5F50]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            @break
                        @case('fuel')
                            <svg class="w-5 h-5 text-[#2D5F50]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21v-4.5a2.5 2.5 0 00-5 0V21M3 21V5a2 2 0 012-2h8a2 2 0 012 2v16 M9 7H6 M9 10H6" />
                            </svg>
                            @break
                        @case('flight')
                            <svg class="w-5 h-5 text-[#2D5F50]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            @break
                        @case('groceries')
                            <svg class="w-5 h-5 text-[#2D5F50]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            @break
                        @case('electronics')
                            <svg class="w-5 h-5 text-[#2D5F50]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="7" y="2" width="10" height="20" rx="2" ry="2" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 18h2" />
                            </svg>
                            @break
                        @default
                            <svg class="w-5 h-5 text-[#2D5F50]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                    @endswitch
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold truncate" style="color: var(--eco-text-primary)">{{ $trx->merchant_name }}</p>
                    <p class="text-xs" style="color: var(--eco-text-secondary)">
                        {{ $trx->category?->name ?? 'Tidak dikategorikan' }} ·
                        {{ \Carbon\Carbon::parse($trx->transacted_at)->format('d M Y') }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    @if($co2e)
                        <span class="eco-badge eco-badge--{{ $level }}">{{ $co2e }} kg</span>
                    @endif
                    @if($trx->amount)
                        <span class="text-sm font-medium" style="color: var(--eco-text-secondary)">Rp {{ number_format($trx->amount, 0, ',', '.') }}</span>
                    @endif
                    <button wire:click="confirmDelete({{ $trx->id }})"
                        class="text-xs px-2.5 py-1.5 rounded-lg transition active:scale-[0.98] active:translate-y-0.5 flex items-center justify-center"
                        style="color: var(--eco-danger); background: rgba(239,68,68,0.08);"
                        title="Hapus">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
        @empty
            <div class="bento-card text-center py-12">
                <svg class="w-12 h-12 text-[#2D5F50] mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19V6m0 0a5 5 0 015 5v3a5 5 0 01-5 5m0-13a5 5 0 00-5 5v3a5 5 0 005 5" />
                </svg>
                <p class="font-semibold" style="color: var(--eco-text-primary)">Belum ada riwayat</p>
                <p class="text-sm mt-1" style="color: var(--eco-text-secondary)">Mulai catat aktivitasmu di kalkulator</p>
                <a href="{{ route('calculator') }}" class="eco-btn eco-btn-primary inline-flex mt-4 active:scale-[0.98] active:translate-y-0.5">Mulai Hitung</a>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div>{{ $transactions->links() }}</div>

</div>
