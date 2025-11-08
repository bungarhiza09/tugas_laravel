<div class="mt-3">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="flex-fill">
                {{-- Tombol Kembali --}}
                <a href="{{ route('app.home') }}" class="text-decoration-none">
                    <small class="text-muted">
                        &lt; Kembali
                    </small>
                </a>

                {{-- Judul dan Status Transaksi --}}
                <h3 class="mt-1">
                    {{ $transaction->keterangan }}
                    @if ($transaction->jenis === 'pemasukan')
                        <small class="badge bg-success">Pemasukan</small>
                    @else
                        <small class="badge bg-danger">Pengeluaran</small>
                    @endif
                </h3>
            </div>

            {{-- Tombol Ubah Cover --}}
            <div>
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editCoverTransactionModal">
                    <i class="bi bi-image"></i> Ubah Cover
                </button>
            </div>
        </div>

        <div class="card-body">
            {{-- Gambar Cover --}}
            @if ($transaction->cover)
                <img src="{{ asset('storage/' . $transaction->cover) }}" alt="Cover" class="img-fluid rounded mb-3" style="max-height: 300px; object-fit: cover;">
                <hr>
            @endif

            {{-- Detail Transaksi --}}
            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($transaction->tanggal)->format('d M Y') }}</p>
            <p><strong>Nominal:</strong> Rp {{ number_format($transaction->nominal, 0, ',', '.') }}</p>
            <p><strong>Keterangan:</strong></p>
            <p class="fs-5">{{ $transaction->deskripsi ?? '-' }}</p>

            {{-- Tombol Aksi --}}
            <div class="mt-4">
                <button class="btn btn-primary me-2" wire:click="showEditModal({{ $transaction->id }})">
                    <i class="bi bi-pencil-square"></i> Edit
                </button>
                <button class="btn btn-danger" wire:click="showDeleteModal({{ $transaction->id }})">
                    <i class="bi bi-trash"></i> Hapus
                </button>
            </div>
        </div>
    </div>

    {{-- Modals --}}
    @include('components.modals.transactions.edit-cover')
    @include('components.modals.transactions.edit')
    @include('components.modals.transactions.delete')
</div>
