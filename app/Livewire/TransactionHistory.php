<?php

namespace App\Livewire;

use App\Models\Transaction;
use App\Models\Category;
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
