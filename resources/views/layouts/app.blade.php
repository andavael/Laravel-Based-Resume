<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FDC User')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="@yield('bodyClass')">
    
    {{-- Page Content --}}
    @yield('content')

    {{-- Footer --}}
    <footer class="@yield('footerClass', 'landing-footer')">
        <img src="{{ asset('assets/black.png') }}" alt="Black Icon" class="landing-icon">
        Anda Vael
    </footer>
</body>
</html>