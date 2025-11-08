<!doctype html>
<html lang="id">

<head>
    {{-- Meta --}}
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Icon --}}
    <link rel="icon" href="/logo.png" type="image/x-icon" />

    {{-- Judul --}}
    <title>KeuanganKu | Autentikasi</title>

    {{-- Styles --}}
    @livewireStyles
    <link rel="stylesheet" href="/assets/vendor/bootstrap-5.3.8-dist/css/bootstrap.min.css">

    {{-- Custom Style --}}
    <style>
        body {
            background: linear-gradient(135deg, #007bff, #00bcd4);
            font-family: "Segoe UI", Roboto, Arial, sans-serif;
            color: #333;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 420px;
        }

        .auth-card h3 {
            font-weight: 700;
            color: #007bff;
        }

        .form-control {
            border-radius: 8px;
        }

        .btn-primary {
            background: linear-gradient(90deg, #007bff, #00bcd4);
            border: none;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #0069d9, #00acc1);
        }

        .auth-footer {
            margin-top: 15px;
            text-align: center;
            font-size: 0.9rem;
            color: #6c757d;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    {{-- Kontainer utama untuk halaman login/register --}}
    <div class="auth-card">
        <div class="text-center mb-4">
            <img src="/logo.png" alt="Logo" width="70" class="mb-2">
            <h3>MyMoney</h3>
            <p class="text-muted mb-0">Catatan Keuangan Pribadi</p>
        </div>

        {{-- Tempat konten halaman login/register --}}
        @yield('content')

        <div class="auth-footer mt-4">
            <p>© {{ date('Y') }} <strong>MyMoney</strong>. Semua Hak Dilindungi.</p>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="/assets/vendor/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
</body>

</html>
