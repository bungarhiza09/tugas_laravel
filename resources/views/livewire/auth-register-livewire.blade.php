<form wire:submit.prevent="register">
    <div class="card shadow mx-auto" style="max-width: 400px; border-radius: 16px; background: #fef9f6;">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <img src="/logo.png" alt="Logo" width="80" class="mb-2">
                <h3 class="fw-bold" style="color: #5a4634;">Daftar Akun</h3>
                <p class="text-muted">Buat akun baru untuk mulai mencatat keuangan Anda</p>
            </div>

            {{-- Nama Lengkap --}}
            <div class="form-group mb-3">
                <label for="name" class="fw-semibold">Nama Lengkap</label>
                <input type="text" id="name" class="form-control" wire:model.defer="name" placeholder="Nama Lengkap"
                    style="border-radius: 12px; border: 1px solid #ccc;">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Email --}}
            <div class="form-group mb-3">
                <label for="email" class="fw-semibold">Alamat Email</label>
                <input type="email" id="email" class="form-control" wire:model.defer="email" placeholder="nama@email.com"
                    style="border-radius: 12px; border: 1px solid #ccc;">
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Password --}}
            <div class="form-group mb-3">
                <label for="password" class="fw-semibold">Kata Sandi</label>
                <input type="password" id="password" class="form-control" wire:model.defer="password" placeholder="********"
                    style="border-radius: 12px; border: 1px solid #ccc;">
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Tombol Daftar --}}
            <div class="d-grid mt-4">
                <button type="submit" class="btn fw-semibold py-2"
                    style="background: linear-gradient(90deg, #4CAF50, #81C784); color: white; border-radius: 12px;">
                    <i class="bi bi-person-plus me-1"></i> Daftar
                </button>
            </div>

            <hr style="border-color: #ddd;">

            <p class="text-center mb-0">
                Sudah punya akun?
                <a href="{{ route('auth.login') }}" class="text-decoration-none fw-semibold" style="color: #4CAF50;">
                    Masuk Sekarang
                </a>
            </p>
        </div>
    </div>
</form>
