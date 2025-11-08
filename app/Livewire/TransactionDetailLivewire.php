<?php

namespace App\Livewire;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class TransactionDetailLivewire extends Component
{
    use WithFileUploads;

    public $transaction;
    public $auth;

    // Properti untuk upload gambar baru
    public $editTransactionImageFile;

    public function mount()
    {
        $this->auth = Auth::user();

        // Ambil id dari parameter URL
        $transaction_id = request()->route('transaction_id');
        $targetTransaction = Transaction::where('id', $transaction_id)->first();

        // Jika tidak ditemukan, kembali ke beranda
        if (!$targetTransaction) {
            return redirect()->route('app.home');
        }

        $this->transaction = $targetTransaction;
    }

    public function render()
    {
        return view('livewire.transaction-detail-livewire');
    }

    /**
     * Fungsi untuk mengubah / memperbarui gambar bukti transaksi
     */
    public function editTransactionImage()
    {
        $this->validate([
            'editTransactionImageFile' => 'required|image|max:2048', // Maks 2MB
        ]);

        if ($this->editTransactionImageFile) {
            // Hapus gambar lama jika ada
            if ($this->transaction->image && Storage::disk('public')->exists($this->transaction->image)) {
                Storage::disk('public')->delete($this->transaction->image);
            }

            // Simpan gambar baru
            $userId = $this->auth->id;
            $timestamp = now()->format('YmdHis');
            $extension = $this->editTransactionImageFile->getClientOriginalExtension();
            $filename = "transaction_{$userId}_{$timestamp}.{$extension}";

            $path = $this->editTransactionImageFile->storeAs('transaction_images', $filename, 'public');

            // Update ke database
            $this->transaction->image = $path;
            $this->transaction->save();
        }

        // Reset input dan tutup modal
        $this->reset(['editTransactionImageFile']);
        $this->dispatch('closeModal', id: 'editTransactionImageModal');
    }
}
