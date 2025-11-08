<div>
    <!-- Button untuk buka modal -->
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
        Tambah Transaksi
    </button>

    <!-- Modal Tambah Transaksi -->
    <div class="modal fade" id="addTransactionModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog">
            <form wire:submit.prevent="addTransaction" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Jenis Transaksi</label>
                        <select class="form-select" wire:model="addTransactionType">
                            <option value="income">Pemasukan</option>
                            <option value="expense">Pengeluaran</option>
                        </select>
                        @error('addTransactionType') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Judul</label>
                        <input type="text" class="form-control" wire:model="addTransactionTitle">
                        @error('addTransactionTitle') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah</label>
                        <input type="number" class="form-control" wire:model="addTransactionAmount">
                        @error('addTransactionAmount') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" wire:model="addTransactionDescription"></textarea>
                        @error('addTransactionDescription') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" class="form-control" wire:model="addTransactionDate">
                        @error('addTransactionDate') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload Bukti</label>
                        <input type="file" class="form-control" wire:model="addTransactionImage">
                        @error('addTransactionImage') <span class="text-danger">{{ $message }}</span> @enderror
                        <div wire:loading wire:target="addTransactionImage" class="text-muted mt-1">
                            Mengunggah gambar...
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.addEventListener('close-modal', event => {
            const modal = new bootstrap.Modal(document.getElementById(event.detail.id));
            modal.hide();
        });
    </script>
</div>
