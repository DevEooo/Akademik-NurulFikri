<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'STT Nurul Fikri')</title>

    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    
    @stack('styles')
</head>
<body>

    <header>
        <nav class="navbar ...">
             <img src="{{ asset('frontend/img/logo.png') }}" alt="Logo">
             <ul>
                 <li><a href="{{ route('home') }}">Beranda</a></li>
                 <li><a href="{{ route('berita') }}">Berita</a></li>
                 <li><a href="{{ url('/portal/login') }}">Login Portal</a></li>
             </ul>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} STT Nurul Fikri</p>
    </footer>

    <script src="{{ asset('frontend/js/jquery.min.js') }}"></script>
    <script src="{{ asset('frontend/js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>