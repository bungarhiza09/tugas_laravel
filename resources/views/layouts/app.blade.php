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
    <title>Catatan Keuangan Pribadi</title>

    {{-- Styles --}}
    @livewireStyles
    <link rel="stylesheet" href="/assets/vendor/bootstrap-5.3.8-dist/css/bootstrap.min.css">

    <style>
        body {
            background: #FFFFFF;
            font-family: "Segoe UI", Roboto, Arial, sans-serif;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        nav.navbar {
            background: linear-gradient(90deg, #ffd7ba, #ffe1c6);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }

        nav a.navbar-brand {
            color: #5a4634 !important;
            font-weight: 700;
            font-size: 1.4rem;
        }

        .nav-link {
            color: #7c6a54 !important;
            font-weight: 500;
        }

        .nav-link:hover {
            color: #5a4634 !important;
            text-decoration: underline;
        }

        main.container {
            flex: 1;
            padding: 40px 0;
        }

        footer {
            background: #fff;
            border-top: 1px solid #eaeaea;
            color: #6c757d;
            text-align: center;
            padding: 15px 0;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/app/home') }}">💰 MyMoney</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a href="{{ route('auth.logout') }}" class="nav-link text-warning">🚪 Logout</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('auth.login') }}" class="nav-link">🔐 Login</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('auth.register') }}" class="nav-link">📝 Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    {{-- Konten --}}
    <main class="container">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer>
        © {{ date('Y') }} <strong>MyMoney</strong> — Aplikasi Catatan Keuangan Pribadi
    </footer>

    {{-- Scripts --}}
    <script src="/assets/vendor/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>

    {{-- Livewire Modal Events --}}
    <script>
        document.addEventListener("livewire:initialized", () => {
            Livewire.on("closeModal", (data) => {
                const modal = bootstrap.Modal.getInstance(document.getElementById(data.id));
                if (modal) modal.hide();
            });

            Livewire.on("showModal", (data) => {
                const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById(data.id));
                if (modal) modal.show();
            });
        });
    </script>

    @livewireScripts
</body>

</html>
