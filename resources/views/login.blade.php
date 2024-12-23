<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Dashboard</title>

    <link rel="icon" href="{{ asset('lib/default_media/logos.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('lib/css/style.css') }}" />
    <!-- ... kode lainnya ... -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
  <body class="bg-gray-100">
    @include('components.notification')
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
        <!-- Logo -->
        <div class="flex justify-center mb-8">
          <img src="{{ asset('lib/default_media/logos.png') }}" alt="Logo" class="h-12 w-auto" />
        </div>

        <!-- Title -->
        <h2 class="text-center text-2xl font-bold text-gray-900 mb-8">
          Login Dashboard
        </h2>

        @if(session()->has('error'))
          <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
          </div>
        @endif

        @if(session()->has('success'))
          <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
          </div>
        @endif

        <!-- Form -->
        <form class="space-y-6" action="{{ route('login') }}" method="POST">
          @csrf
          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
              Email
            </label>
            <div class="mt-1 relative">
              <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                <i class="fas fa-envelope text-gray-400"></i>
              </span>
              <input
                id="email"
                name="email"
                type="email"
                required
                class="appearance-none block w-full pl-10 pr-3 py-2 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-gray-500 focus:border-gray-500 sm:text-sm"
                placeholder="Masukkan email"
                value="{{ old('email') }}"
              />
            </div>
            @error('email')
              <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
          </div>

          <!-- Password -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">
              Password
            </label>
            <div class="mt-1 relative">
              <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                <i class="fas fa-lock text-gray-400"></i>
              </span>
              <input
                id="password"
                name="password"
                type="password"
                required
                class="appearance-none block w-full pl-10 pr-3 py-2 border @error('password') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-gray-500 focus:border-gray-500 sm:text-sm"
                placeholder="Masukkan password"
              />
            </div>
            @error('password')
              <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
          </div>

          <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#a10d05] hover:bg-[#8f0b04] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
            Login
          </button>
        </form>
        <p class="text-center mt-4 text-sm text-gray-600">
            Tidak mempunyai akun?
            <a href="{{route('register')}}" class="font-medium text-[#a10d05] hover:text-[#8f0b04]">Buat sekarang juga</a>
        </p>
      </div>
    </div>

    @if(session()->has('notification'))
    <script>
      Swal.fire({
        icon: '{{ session('notification.type') }}',
        title: '{{ session('notification.title') }}',
        text: '{{ session('notification.message') }}',
        timer: 3000,
        timerProgressBar: true
      });
    </script>
    @endif
  </body>
</html>
