<div class="mt-4">
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Hai, {{ $auth->name }} 👋</h4>
        <a href="{{ route('auth.logout') }}" class="btn btn-outline-secondary btn-sm">Keluar</a>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm rounded-4 p-3 text-center" style="background:#b3f0ca;">
                <h6 class="fw-semibold text-dark">Total Pemasukan</h6>
                <h4 class="fw-bold text-dark">
                    Rp {{ number_format($allTransactions->where('type','income')->sum('amount'), 0, ',', '.') }}
                </h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm rounded-4 p-3 text-center" style="background:#ffb3b3;">
                <h6 class="fw-semibold text-dark">Total Pengeluaran</h6>
                <h4 class="fw-bold text-dark">
                    Rp {{ number_format($allTransactions->where('type','expense')->sum('amount'), 0, ',', '.') }}
                </h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm rounded-4 p-3 text-center" style="background:#b3d9ff;">
                <h6 class="fw-semibold text-dark">Saldo</h6>
                <h4 class="fw-bold text-dark">
                    Rp {{ number_format($allTransactions->where('type','income')->sum('amount') - $transactions->where('type','expense')->sum('amount'), 0, ',', '.') }}
                </h4>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="mb-3">Statistik Keuangan</h5>
            <div id="chart"></div>
        </div>
    </div>

    <!-- FORM FILTER DAN TAMBAH -->
    <form class="d-flex flex-wrap align-items-center gap-2 mb-3" wire:submit.prevent="searchTransactions">
        <!-- Input pencarian -->
        <div class="flex-grow-1">
            <input type="text" class="form-control" placeholder="Cari transaksi..." wire:model="search">
        </div>

        <!-- Filter jenis transaksi -->
        <div>
            <select class="form-select" wire:model="filterType">
                <option value="all">Semua</option>
                <option value="income">Pemasukan</option>
                <option value="expense">Pengeluaran</option>
            </select>
        </div>

        <!-- Tombol Cari -->
        <div>
            <button type="submit" class="btn btn-primary d-flex align-items-center">
                <i class="bi bi-search me-1"></i> Cari
            </button>
        </div>

        <!-- Tombol Tambah -->
        <div>
            <button type="button" class="btn btn-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                <i class="bi bi-plus-circle me-1"></i> Tambah
            </button>
        </div>
    </form>

    <!-- DAFTAR TRANSAKSI -->
    <div class="table-responsive">
        <table class="table table-hover align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Keterangan</th>
                    <th>Nominal</th>
                    <th>Bukti</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $index => $item)
                <tr>
                    <td>{{ $transactions->firstItem() + $index }}</td>
                    <td class="text-start">{{ $item->title }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->transaction_date)->format('d/m/Y') }}</td>
                    <td>
                        <span class="badge rounded-pill {{ $item->type == 'income' ? 'bg-success' : 'bg-danger' }}">
                            {{ $item->type == 'income' ? 'Income' : 'Expense' }}
                        </span>
                    </td>
                    <td class="text-start">{{ $item->description ?? '-' }}</td>
                    <td>Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                    <td>
                        @if($item->image)
                        <img src="{{ asset('storage/'.$item->image) }}" class="img-fluid rounded" width="50">
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex justify-content-center gap-1">
                            <button class="btn btn-sm btn-warning" wire:click="prepareEditTransaction({{ $item->id }})">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" wire:click="prepareDeleteTransaction({{ $item->id }})">
                                <i class="bi bi-trash"></i>
                            </button>
                            <button class="btn btn-sm btn-secondary" wire:click="prepareEditTransactionCover({{ $item->id }})">
                                <i class="bi bi-image"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-3">Belum ada catatan keuangan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    @if ($transactions->hasPages())
    <ul class="pagination justify-content-center mt-3">
        @if ($transactions->onFirstPage())
            <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
        @else
            <li class="page-item"><a href="#" wire:click.prevent="previousPage" class="page-link">&laquo;</a></li>
        @endif

        @foreach ($transactions->links()->elements[0] as $page => $url)
            <li class="page-item {{ $page == $transactions->currentPage() ? 'active' : '' }}">
                <a href="#" wire:click.prevent="gotoPage({{ $page }})" class="page-link">{{ $page }}</a>
            </li>
        @endforeach

        @if ($transactions->hasMorePages())
            <li class="page-item"><a href="#" wire:click.prevent="nextPage" class="page-link">&raquo;</a></li>
        @else
            <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
        @endif
    </ul>
    @endif

    <!-- MODALS -->
    @include('components.modals.transactions.add')
    @include('components.modals.transactions.edit')
    @include('components.modals.transactions.delete')
    @include('components.modals.transactions.edit-cover')

    <!-- SCRIPTS -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('swal', data => {
            Swal.fire({
                title: data.title || 'Info',
                text: data.text || '',
                icon: data.icon || 'info',
            });
        });

        Livewire.on('openModal', data => {
            const modalEl = document.getElementById(data.id);
            if (modalEl) new bootstrap.Modal(modalEl).show();
        });

        Livewire.on('closeModal', data => {
            const modalEl = document.getElementById(data.id);
            if (modalEl) bootstrap.Modal.getInstance(modalEl)?.hide();
        });
    });
    </script>

<!-- SCRIPT APEXCHARTS -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener('livewire:navigated', () => {
    const chartData = @json($chartData);

    // Hapus chart lama biar tidak dobel saat navigasi
    document.querySelector("#chart").innerHTML = "";

    // Cek kalau datanya kosong
    if (!chartData.series || chartData.series.length === 0 || !chartData.categories) {
        document.querySelector("#chart").innerHTML = "<p class='text-muted'>Tidak ada data untuk ditampilkan.</p>";
        return;
    }

    const options = {
        chart: {
            type: 'line',
            height: 300,
            toolbar: { show: false }
        },
        series: chartData.series, // langsung pakai series dari PHP
        xaxis: {
            categories: chartData.categories
        },
        stroke: { curve: 'smooth' },
        colors: ['#4CAF50', '#F44336'], // hijau utk income, merah utk expense
        markers: { size: 4 },
        tooltip: { theme: 'light' },
        legend: { position: 'top' }
    };

    const chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
});
</script>

