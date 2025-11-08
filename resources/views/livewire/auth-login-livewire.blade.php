<form wire:submit.prevent="login">
    <div class="card shadow mx-auto" style="max-width: 380px; border-radius: 16px; background: #fef9f6;">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <img src="/logo.png" alt="Logo" width="80" class="mb-2">
                <h3 class="fw-bold" style="color: #5a4634;">Catatan Keuangan</h3>
                <p class="text-muted">Masuk ke akun Anda untuk mengelola keuangan</p>
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

            {{-- Tombol Login --}}
            <div class="d-grid mt-4">
                <button type="submit" class="btn fw-semibold py-2" 
                    style="background: linear-gradient(90deg, #4CAF50, #81C784); color: white; border-radius: 12px;">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                </button>
            </div>

            <hr style="border-color: #ddd;">

            <p class="text-center mb-0">
                Belum punya akun?
                <a href="{{ route('auth.register') }}" class="text-decoration-none fw-semibold" style="color: #4CAF50;">
                    Daftar Sekarang
                </a>
            </p>
        </div>
    </div>
</form>
