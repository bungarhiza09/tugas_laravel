<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class HomeLivewire extends Component
{
    use WithFileUploads, WithPagination;

    public $currentImage;         
    public $newTransactionImage;  
    public $editTransactionIdForImage;
    public $auth;

    public $search = ''; // untuk pencarian
    public $filterType = 'all'; // all, income, expense

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshChart' => 'loadChartData'];

    // Tambah transaksi
    public $addTransactionType = 'income';
    public $addTransactionTitle;
    public $addTransactionAmount;
    public $addTransactionDescription;
    public $addTransactionDate;
    public $addTransactionImage;

    // Edit transaksi
    public $editTransactionId;
    public $editTransactionType;
    public $editTransactionTitle;
    public $editTransactionAmount;
    public $editTransactionDescription;
    public $editTransactionDate;

    // Delete transaksi
    public $deleteTransactionId;
    public $deleteTransactionTitle;
    public $deleteTransactionConfirmTitle;
    public $chartData = [];
    public $filter = 'income';

    public function mount()
    {
        $this->auth = Auth::user();
        $this->loadChartData();
    }

    public function updated()
    {
        $this->loadChartData();
        $this->dispatch('updateChart', $this->chartData);
        $this->loadChartData();
    }

    // Reset halaman saat search atau filter berubah
    public function updatingSearch()
    {
        $this->loadChartData();
        $this->dispatch('updateChart', $this->chartData);
        $this->resetPage();
    }

    public function searchTransactions()
    {
        $this->loadChartData();
        $this->dispatch('updateChart', $this->chartData);
        $this->resetPage();
    }

    public function updatingFilterType()
    {
        $this->loadChartData();
        $this->dispatch('updateChart', $this->chartData);
        $this->resetPage();
    }

    
    public function render()
{
    $query = Transaction::where('user_id', $this->auth->id);

    // Filter tipe transaksi
    if ($this->filterType != 'all') {
        $query->where('type', $this->filterType);
    }

    // Pencarian berdasarkan judul atau deskripsi
    if ($this->search) {
        $query->where(function($q) {
            $q->whereRaw('title ILIKE ?', ['%' . $this->search . '%'])
              ->orWhereRaw('description ILIKE ?', ['%' . $this->search . '%']);
        });
    }

    // Pagination
    $transactions = $query->orderBy('transaction_date', 'desc')->paginate(20);
    $allTransactions = Transaction::where('user_id', $this->auth->id)->get();

    // Data untuk chart (hanya dari hasil query halaman ini)
    $labels = $transactions->pluck('transaction_date')->map(fn($d) => date('d M', strtotime($d)));
    $amounts = $transactions->pluck('amount');

    $chartData = [
        'labels' => $labels,
        'amounts' => $amounts,
    ];

    // Hitung total dari seluruh transaksi user (tanpa paginate)
    $allTransactions = Transaction::where('user_id', $this->auth->id)->get();
    $totalIncome = $allTransactions->where('type', 'income')->sum('amount');
    $totalExpense = $allTransactions->where('type', 'expense')->sum('amount');
    $balance = $totalIncome - $totalExpense;

    return view('livewire.home-livewire', [
        'allTransactions' => $allTransactions,
        'transactions' => $transactions,
        'chartData' => [
            'labels' => $labels->values(),
            'amounts' => $amounts->values(),
        ],
        'totalIncome' => $transactions->where('type', 'income')->sum('amount'),
        'totalExpense' => $transactions->where('type', 'expense')->sum('amount'),
        'balance' => $transactions->sum(fn($t) => $t->type === 'income' ? $t->amount : -$t->amount),
    ]);
}


    // ========================
    // TAMBAH TRANSAKSI
    // ========================
    public function addTransaction()
    {
        try{
            $this->validate([
                'addTransactionType' => 'required|in:income,expense',
                'addTransactionTitle' => 'required|string|max:255',
                'addTransactionAmount' => 'required|numeric|min:0',
                'addTransactionDescription' => 'nullable|string',
                'addTransactionDate' => 'required|date',
                'addTransactionImage' => 'nullable|image|max:2048',
            ]);

            $imagePath = null;
            if ($this->addTransactionImage) {
                $imagePath = $this->addTransactionImage->store('transactions', 'public');
            }

            Transaction::create([
                'user_id' => $this->auth->id,
                'type' => $this->addTransactionType,
                'title' => $this->addTransactionTitle,
                'amount' => $this->addTransactionAmount,
                'description' => $this->addTransactionDescription,
                'transaction_date' => $this->addTransactionDate,
                'image' => $imagePath,
            ]);

            $this->reset(['addTransactionType','addTransactionTitle','addTransactionAmount','addTransactionDescription','addTransactionDate','addTransactionImage']);
            $this->dispatch('closeModal', id: 'addTransactionModal');
            $this->dispatch('swal', title: 'Berhasil!', text: 'Transaksi berhasil disimpan!', icon: 'success');
        } catch (\Exception $e) {
             $this->dispatch('swal', title: 'Gagal!', text: 'Terjadi kesalahan: '.$e->getMessage(), icon: 'error');
        }
    }

    // ========================
    // EDIT TRANSAKSI
    // ========================
    public function prepareEditTransaction($id)
    {
        $t = Transaction::find($id);
        if (!$t) return;

        $this->editTransactionId = $t->id;
        $this->editTransactionType = $t->type;
        $this->editTransactionTitle = $t->title;
        $this->editTransactionAmount = $t->amount;
        $this->editTransactionDescription = $t->description;
        $this->editTransactionDate = $t->transaction_date;

         $this->dispatch('openModal', id: 'editTransactionModal');
    }

    public function editTransaction()
    {
        try{
            $this->validate([
                'editTransactionType' => 'required|in:income,expense',
                'editTransactionTitle' => 'required|string|max:255',
                'editTransactionAmount' => 'required|numeric|min:0',
                'editTransactionDescription' => 'nullable|string',
                'editTransactionDate' => 'required|date',
            ]);

            $t = Transaction::find($this->editTransactionId);
            if (!$t) return;

            $t->update([
                'type' => $this->editTransactionType,
                'title' => $this->editTransactionTitle,
                'amount' => $this->editTransactionAmount,
                'description' => $this->editTransactionDescription,
                'transaction_date' => $this->editTransactionDate,
            ]);

            $this->reset(['editTransactionId','editTransactionType','editTransactionTitle','editTransactionAmount','editTransactionDescription','editTransactionDate']);
            $this->dispatch('closeModal', id: 'editTransactionModal'); 
            $this->dispatch('swal', title: 'Berhasil!', text: 'Transaksi berhasil diubah!', icon: 'success');
        } catch (\Exception $e) {
            $this->dispatch('swal', title: 'Gagal!', text: 'Terjadi kesalahan: '.$e->getMessage(), icon: 'error');
        }
    }

    // ========================
    // HAPUS TRANSAKSI
    // ========================
    public function prepareDeleteTransaction($id)
    {
        $t = Transaction::find($id);
        if (!$t) return;

        $this->deleteTransactionId = $t->id;
        $this->deleteTransactionTitle = $t->title;

         $this->dispatch('openModal', id: 'deleteTransactionModal');
    }

    public function deleteTransaction()
    {
        try{
            if ($this->deleteTransactionConfirmTitle !== $this->deleteTransactionTitle) {
                $this->addError('deleteTransactionConfirmTitle', 'Judul konfirmasi tidak sesuai.');
                return;
            }

            Transaction::destroy($this->deleteTransactionId);
            $this->reset(['deleteTransactionId','deleteTransactionTitle','deleteTransactionConfirmTitle']);
            $this->dispatch('closeModal', id: 'deleteTransactionModal');
            $this->dispatch('swal', title: 'Berhasil!', text: 'Transaksi berhasil dihapus!', icon: 'success');
        } catch (\Exception $e) {
            $this->dispatch('swal', title: 'Gagal!', text: 'Terjadi kesalahan: '.$e->getMessage(), icon: 'error');
        }
    }

    // ========================
    // EDIT COVER (GAMBAR)
    // ========================
    public function prepareEditTransactionCover($id)
    {
        $t = Transaction::find($id);
        if (!$t) return;

        $this->editTransactionIdForImage = $t->id;
        $this->currentImage = $t->image;

         $this->dispatch('openModal', id: 'editCoverModal');
    }

    public function editCover()
{
    try {
        $this->validate([
            'newTransactionImage' => 'required|image|max:2048',
        ]);

        $t = Transaction::find($this->editTransactionIdForImage);
        if (!$t) return;

        $imagePath = $this->newTransactionImage->store('transactions', 'public');

        $t->update(['image' => $imagePath]);

        // Tutup modal dulu
        $this->dispatch('closeModal', id: 'editCoverModal');

        // Reset properti & validasi setelah modal tertutup
        $this->reset(['editTransactionIdForImage','currentImage','newTransactionImage']);
        $this->resetValidation();

        $this->dispatch('swal', title: 'Berhasil!', text: 'Bukti transaksi berhasil diubah!', icon: 'success');

    } catch (\Exception $e) {
        $this->dispatch('swal', title: 'Gagal!', text: 'Terjadi kesalahan: '.$e->getMessage(), icon: 'error');
    }
}


    public function loadChartData()
{
    // Query dasar
    $query = \DB::table('transactions')
        ->selectRaw("TO_CHAR(transaction_date, 'Mon YYYY') AS bulan")
        ->selectRaw("SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) AS total_income")
        ->selectRaw("SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) AS total_expense")
        ->where('user_id', $this->auth->id)
        ->groupBy('bulan')
        ->orderByRaw("MIN(transaction_date)");

    // Terapkan filter dari dropdown di atas tabel
    if ($this->filterType !== 'all') {
        $query->where('type', $this->filterType);
    }

    // Terapkan pencarian
    if ($this->search) {
        $query->where(function ($q) {
            $q->whereRaw('title ILIKE ?', ['%' . $this->search . '%'])
              ->orWhereRaw('description ILIKE ?', ['%' . $this->search . '%']);
        });
    }

    $data = $query->get();

    // Siapkan data untuk ApexCharts
    $this->chartData = [
        'series' => [
            [
                'name' => 'Pemasukan',
                'data' => $data->pluck('total_income')->toArray(),
            ],
            [
                'name' => 'Pengeluaran',
                'data' => $data->pluck('total_expense')->toArray(),
            ],
        ],
        'categories' => $data->pluck('bulan')->toArray() ?: ['Tidak ada data'],
    ];

    // Kirim data ke JavaScript chart
    $this->dispatch('updateChart', $this->chartData);
}


}
