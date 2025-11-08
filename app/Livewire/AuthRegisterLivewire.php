<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class AuthRegisterLivewire extends Component
{
    public $name;
    public $email;
    public $password;

    public function register()
    {
        // Validasi input
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        // Simpan user baru ke database
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        // Reset input setelah berhasil
        $this->reset(['name', 'email', 'password']);

        // Redirect ke halaman login
        session()->flash('success', 'Pendaftaran berhasil! Silakan login.');
        return redirect()->route('auth.login');
    }

    public function render()
    {
        return view('livewire.auth-register-livewire');
    }
}
