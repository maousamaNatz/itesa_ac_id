<aside id="sidebar" class="fixed top-0 -left-64 lg:left-0 z-30 w-64 h-screen transition-all duration-300 bg-white">
    <div class="flex items-center justify-center h-16 border-b">
        <img src="{{ asset('lib/default_media/logos.png') }}" alt="logo itesa" class="h-10 w-10 mr-2">
        <div>
            <h2 class="text-[#a10d05] text-lg font-bold">ITESA</h2>
            <p class="text-gray-600 text-xs">Portal Berita</p>
        </div>
    </div>

    <nav class="mt-6">
        <div class="px-4 py-2">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center px-4 py-3 {{ Request::routeIs('admin.dashboard') ? 'text-white bg-[#a10d05]' : 'text-gray-600 hover:text-[#a10d05] hover:bg-gray-100' }} rounded-lg">
                <i class="fas fa-home mr-3"></i>
                <span>Beranda</span>
            </a>

            <a href="{{ route('admin.article.index') }}"
               class="flex items-center px-4 py-3 mt-2 {{ Request::routeIs('admin.article.*') ? 'text-white bg-[#a10d05]' : 'text-gray-600 hover:text-[#a10d05] hover:bg-gray-100' }} rounded-lg">
                <i class="fas fa-newspaper mr-3"></i>
                <span>Artikel</span>
            </a>

            <a href="{{ route('admin.category.index') }}"
               class="flex items-center px-4 py-3 mt-2 {{ Request::routeIs('admin.category.*') ? 'text-white bg-[#a10d05]' : 'text-gray-600 hover:text-[#a10d05] hover:bg-gray-100' }} rounded-lg">
                <i class="fas fa-list mr-3"></i>
                <span>Kategori</span>
            </a>

            <a href="{{ route('admin.comment.index') }}"
               class="flex items-center px-4 py-3 mt-2 {{ Request::routeIs('admin.comment.*') ? 'text-white bg-[#a10d05]' : 'text-gray-600 hover:text-[#a10d05] hover:bg-gray-100' }} rounded-lg">
                <i class="fas fa-comments mr-3"></i>
                <span>Komentar</span>
            </a>
            <a href="{{ route('admin.agenda.index') }}"
               class="flex items-center px-4 py-3 mt-2 {{ Request::routeIs('admin.agenda.*') ? 'text-white bg-[#a10d05]' : 'text-gray-600 hover:text-[#a10d05] hover:bg-gray-100' }} rounded-lg">
                <i class="fas fa-calendar mr-3"></i>
                <span>Agenda</span>
            </a>
        </div>
    </nav>

    <div class="absolute bottom-0 left-0 right-0 p-4 border-t">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex w-full items-center px-4 py-3 text-gray-600 hover:text-[#a10d05] hover:bg-gray-100 rounded-lg">
                <i class="fas fa-sign-out-alt mr-3"></i>
                <span>Keluar</span>
            </button>
        </form>
    </div>

    <button id="closeSidebar" class="absolute top-4 right-4 text-gray-600 lg:hidden">
        <i class="fas fa-times text-xl"></i>
    </button>
</aside>
