<form wire:submit.prevent="editTransaction">
    <div class="modal fade" id="editTransactionModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label>Jenis</label>
                    <select class="form-select" wire:model="editTransactionType">
                        <option value="income">Pemasukan</option>
                        <option value="expense">Pengeluaran</option>
                    </select>
                    <label>Judul</label>
                    <input type="text" class="form-control" wire:model="editTransactionTitle">
                    <label>Jumlah</label>
                    <input type="number" class="form-control" wire:model="editTransactionAmount">
                    <label>Deskripsi</label>
                    <textarea class="form-control" wire:model="editTransactionDescription"></textarea>
                    <label>Tanggal</label>
                    <input type="date" class="form-control" wire:model="editTransactionDate">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>