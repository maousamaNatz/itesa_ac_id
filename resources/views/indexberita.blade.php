<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Berita ITESA')</title>
    <link rel="stylesheet" href="{{ asset('lib/css/berita.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{asset('lib/default_media/logos.png')}}" type="x-icon">
</head>
<body>
    @include('components.navbarberita')
    <div class="container">
        <!-- Main Content Grid -->
        @yield('content')
    </div>

    <!-- Footer -->
    @include('components.footerberita')
    @if(Route::is('profile'))
    <script src="{{ asset('lib/js/profile.js') }}"></script>
    @endif
    <script src="{{ asset('lib/js/berita.js') }}"></script>
    @yield('scripts')
</body>
</html>

