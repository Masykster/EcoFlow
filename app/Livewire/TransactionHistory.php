<?php

namespace App\Livewire;

use App\Models\Transaction;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionHistory extends Component
{
    use WithPagination;

    public string $filterCategory = '';
    public string $filterType     = '';
    public string $filterFrom     = '';
    public string $filterTo       = '';

    public array $categories = [];
    public ?int  $confirmDeleteId = null;

    // ── Export Report ────────────────────────────────────────────
    public bool   $showExportModal = false;
    public string $exportMode      = 'monthly'; // daily, monthly, yearly, custom
    public string $exportFrom      = '';
    public string $exportTo        = '';

    public function mount(): void
    {
        $this->categories = Category::orderBy('name')->get(['id', 'name', 'slug'])->toArray();
    }

    public function updatingFilterCategory(): void { $this->resetPage(); }
    public function updatingFilterType(): void      { $this->resetPage(); }
    public function updatingFilterFrom(): void      { $this->resetPage(); }
    public function updatingFilterTo(): void        { $this->resetPage(); }

    public function confirmDelete(int $id): void
    {
        $this->confirmDeleteId = $id;
    }

    public function cancelDelete(): void
    {
        $this->confirmDeleteId = null;
    }

    public function deleteTransaction(): void
    {
        $user = Auth::user();
        $transaction = Transaction::where('user_id', $user->id)->find($this->confirmDeleteId);

        if ($transaction) {
            $transaction->delete();
        }

        $this->confirmDeleteId = null;
        $this->dispatch('transaction-deleted');
    }

    // ── Export Report Methods ────────────────────────────────────

    public function openExportModal(): void
    {
        $this->showExportModal = true;
    }

    public function closeExportModal(): void
    {
        $this->showExportModal = false;
    }

    public function setExportMode(string $mode): void
    {
        $this->exportMode = $mode;
    }

    public function exportReport()
    {
        $user = Auth::user();

        // Resolve date range from mode
        [$from, $to] = match ($this->exportMode) {
            'daily'   => [now()->startOfDay(), now()->endOfDay()],
            'monthly' => [now()->startOfMonth(), now()->endOfMonth()],
            'yearly'  => [now()->startOfYear(), now()->endOfYear()],
            'custom'  => [
                $this->exportFrom ? Carbon::parse($this->exportFrom)->startOfDay() : now()->startOfMonth(),
                $this->exportTo   ? Carbon::parse($this->exportTo)->endOfDay()     : now()->endOfDay(),
            ],
            default   => [now()->startOfMonth(), now()->endOfMonth()],
        };

        $query = Transaction::where('user_id', $user->id)
            ->with('category:id,name,slug')
            ->whereBetween('transacted_at', [$from, $to])
            ->latest('transacted_at');

        if ($this->filterCategory) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $this->filterCategory));
        }
        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        $transactions = $query->get();

        $modeLabel = match ($this->exportMode) {
            'daily'   => 'harian_' . now()->format('Y-m-d'),
            'monthly' => 'bulanan_' . now()->format('Y-m'),
            'yearly'  => 'tahunan_' . now()->format('Y'),
            'custom'  => 'custom_' . ($this->exportFrom ?: 'start') . '_' . ($this->exportTo ?: 'end'),
            default   => 'laporan',
        };

        $filename = "ecoflow_laporan_{$modeLabel}.csv";

        $this->showExportModal = false;

        return response()->streamDownload(function () use ($transactions, $from, $to) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header info
            fputcsv($handle, ['Laporan Emisi Karbon - EcoFlow']);
            fputcsv($handle, ['Periode', Carbon::parse($from)->format('d M Y') . ' - ' . Carbon::parse($to)->format('d M Y')]);
            fputcsv($handle, ['Diekspor pada', now()->format('d M Y H:i')]);
            fputcsv($handle, []);

            // Column headers
            fputcsv($handle, ['Tanggal', 'Merchant', 'Kategori', 'Tipe', 'Jumlah (Rp)', 'CO2e (kg)']);

            $totalCo2e  = 0;
            $totalAmount = 0;

            foreach ($transactions as $trx) {
                $co2e   = $trx->co2e ?? 0;
                $amount = $trx->amount ?? 0;
                $totalCo2e  += $co2e;
                $totalAmount += $amount;

                fputcsv($handle, [
                    Carbon::parse($trx->transacted_at)->format('d/m/Y'),
                    $trx->merchant_name,
                    $trx->category?->name ?? '-',
                    $trx->type === 'spending' ? 'Pengeluaran' : 'Transportasi',
                    number_format($amount, 0, ',', '.'),
                    number_format($co2e, 2, ',', '.'),
                ]);
            }

            // Summary footer
            fputcsv($handle, []);
            fputcsv($handle, ['', '', '', 'TOTAL', number_format($totalAmount, 0, ',', '.'), number_format($totalCo2e, 2, ',', '.')]);
            fputcsv($handle, ['Total Transaksi', count($transactions)]);

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function render()
    {
        $user  = Auth::user();
        $query = Transaction::where('user_id', $user->id)
            ->with('category:id,name,slug')
            ->latest('transacted_at');

        if ($this->filterCategory) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $this->filterCategory));
        }
        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }
        if ($this->filterFrom) {
            $query->where('transacted_at', '>=', $this->filterFrom);
        }
        if ($this->filterTo) {
            $query->where('transacted_at', '<=', $this->filterTo . ' 23:59:59');
        }

        return view('livewire.transaction-history', [
            'transactions' => $query->paginate(10),
        ]);
    }
}
