<div class="p-6 space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--eco-primary)">Riwayat Transaksi</h1>
            <p class="text-sm mt-1" style="color: var(--eco-text-secondary)">Kelola dan filter semua riwayat emisi kamu</p>
        </div>
        <a href="{{ route('calculator') }}" class="eco-btn eco-btn-primary">+ Tambah</a>
    </div>

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
                    <button wire:click="cancelDelete" class="eco-btn eco-btn-secondary flex-1">Batal</button>
                    <button wire:click="deleteTransaction" class="eco-btn eco-btn-danger flex-1">Hapus</button>
                </div>
            </div>
        </div>
    @endif

    {{-- Transaction List --}}
    <div class="space-y-2">
        @php
            $emojiMap = ['food'=>'🍜','transport'=>'🚗','fashion'=>'👕','electricity'=>'⚡','fuel'=>'⛽','flight'=>'✈️','groceries'=>'🛒','electronics'=>'📱'];
        @endphp

        @forelse($transactions as $trx)
            @php
                $slug  = $trx->category?->slug ?? '';
                $emoji = $emojiMap[$slug] ?? '📦';
                $co2e  = $trx->co2e ?? 0;
                $level = $co2e <= 0.5 ? 'success' : ($co2e <= 2.0 ? 'warning' : 'danger');
            @endphp
            <div class="eco-history-item">
                <div class="eco-history-icon" style="background: var(--eco-overlay); font-size: 1.3rem;">{{ $emoji }}</div>
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
                        class="text-xs px-2 py-1 rounded-lg transition"
                        style="color: var(--eco-danger); background: rgba(239,68,68,0.08);"
                        title="Hapus">🗑</button>
                </div>
            </div>
        @empty
            <div class="bento-card text-center py-12">
                <p class="text-4xl mb-3">🌱</p>
                <p class="font-semibold" style="color: var(--eco-text-primary)">Belum ada riwayat</p>
                <p class="text-sm mt-1" style="color: var(--eco-text-secondary)">Mulai catat aktivitasmu di kalkulator</p>
                <a href="{{ route('calculator') }}" class="eco-btn eco-btn-primary inline-flex mt-4">Mulai Hitung</a>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div>{{ $transactions->links() }}</div>

</div>
