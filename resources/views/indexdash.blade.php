<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dashboard Admin Portal Berita ITESA" />
    <title>@yield('title', 'Dashboard Admin - ITESA Muhammadiyah')</title>
    <!-- ... kode lainnya ... -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('lib/default_media/logos.png') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('lib/css/style.css') }}">
    <link rel="icon" href="{{ asset('lib/media/logos.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    @include('components.notification')
    @auth
        <div id="overlay" class="fixed inset-0 bg-black opacity-50 z-20 hidden"></div>

        <div class="flex">
            @include('components.sidebar')

            <main class="w-full lg:ml-64 transition-all duration-300">
                @include('components.navbardash')

                <div class="py-3 px-2 md:p-8">
                    @yield('content')
                </div>
            </main>
        </div>
        @if (request()->routeIs('admin.article.index') || request()->routeIs('admin.dashboard'))
            <script src="{{ asset('lib/js/deleteHandler.js') }}"></script>
        @endif
        @stack('scripts')
    @else
        <script>
            window.location = "{{ route('login') }}";
        </script>
    @endauth
</body>

</html>
