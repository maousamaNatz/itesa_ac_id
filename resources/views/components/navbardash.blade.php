<header class="bg-white shadow-sm sticky top-0" style="z-index: 999;">
    <div class="flex items-center justify-between px-4 lg:px-8 py-4">
        <button id="toggleSidebar" class="text-gray-500 lg:hidden">
            <i class="fas fa-bars text-xl"></i>
        </button>

        <form method="GET" action="{{ route('berita.search') }}" class="relative flex-1 mx-4">

            <input type="text" name="q" placeholder="Cari artikel..."
                   class="w-full lg:w-96 px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-blue-500">
            <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
        </form>

        <div class="flex items-center space-x-4">
            <div class="relative" id="notifDropdown">
                <button class="relative">
                    <i class="fas fa-bell text-gray-500 text-xl"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">3</span>
                </button>
                <div class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg hidden" id="notifContent">
                    <div class="p-4 border-b">
                        <h3 class="font-semibold">Notifikasi</h3>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <!-- Notifikasi items -->
                    </div>
                </div>
            </div>

            <div class="relative" id="profileDropdown">
                <button class="flex items-center space-x-2">
                    @if(Auth::user()->profile_photo)
                        <img src="{{ asset('storage/profile_photos/' . Auth::user()->profile_photo) }}"
                             alt="Profile"
                             class="w-10 h-10 rounded-full object-cover">
                    @else
                        <img src="{{ asset('lib/default_media/default.jpg') }}"
                             alt="Profile Default"
                             class="w-10 h-10 rounded-full">
                    @endif
                    <span class="text-gray-700 hidden lg:inline">{{ Auth::user()->username }}</span>
                </button>
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg hidden" id="profileContent">
                    <div class="px-4 py-2 border-b">
                        <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                    <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-user mr-2"></i>Profil
                    </a>
                    {{-- <a href="{{ route('admin.settings') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-cog mr-2"></i>Pengaturan
                    </a> --}}
                    <aform action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const profileDropdown = document.getElementById('profileDropdown');
    const profileContent = document.getElementById('profileContent');

    profileDropdown.addEventListener('click', function() {
        profileContent.classList.toggle('hidden');
    });

    document.addEventListener('click', function(event) {
        if (!profileDropdown.contains(event.target)) {
            profileContent.classList.add('hidden');
        }
    });
});
</script>
