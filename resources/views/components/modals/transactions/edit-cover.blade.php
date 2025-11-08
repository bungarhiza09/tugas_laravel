<form wire:submit.prevent="editCover">
    <div class="modal fade" id="editCoverModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Gambar Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if ($currentImage)
                        <div class="text-center mb-3">
                            <img src="{{ asset('storage/'.$currentImage) }}" class="img-fluid" width="200">
                        </div>
                    @endif
                    <label>Pilih Gambar Baru</label>
                    <input type="file" class="form-control" wire:model="newTransactionImage">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit">Update Gambar</button>
                </div>
            </div>
        </div>
    </div>
</form>
