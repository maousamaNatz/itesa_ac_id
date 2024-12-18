<header class="main-header navbar-berita">
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-content">
                <div class="top-contact">
                    <span><i class="fas fa-phone"></i> (024) 1234567</span>
                    <span><i class="fas fa-envelope"></i> info@itesa.ac.id</span>
                </div>
                <div class="top-social">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </div>

    <nav class="main-nav">
        <div class="container">
            <div class="nav-wrapper">
                <div class="nav-brand">
                    <img src="{{ asset('lib/default_media/logos.png') }}" alt="ITESA Logo" class="nav-logo">
                    <div class="brand-text">
                        <span class="brand-title">ITESA Muhammadiyah</span>
                        <span class="brand-subtitle">Portal Berita</span>
                    </div>
                </div>

                <div class="nav-search">
                    <form class="search-form" action="{{ route('berita.search') }}" method="get">
                        <input type="search" name="q" placeholder="Cari berita...">
                        <button type="submit"><svg width="800px" height="800px" viewBox="0 -0.5 25 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M7.30524 15.7137C6.4404 14.8306 5.85381 13.7131 5.61824 12.4997C5.38072 11.2829 5.50269 10.0233 5.96924 8.87469C6.43181 7.73253 7.22153 6.75251 8.23924 6.05769C10.3041 4.64744 13.0224 4.64744 15.0872 6.05769C16.105 6.75251 16.8947 7.73253 17.3572 8.87469C17.8238 10.0233 17.9458 11.2829 17.7082 12.4997C17.4727 13.7131 16.8861 14.8306 16.0212 15.7137C14.8759 16.889 13.3044 17.5519 11.6632 17.5519C10.0221 17.5519 8.45059 16.889 7.30524 15.7137V15.7137Z"
                                    stroke="#000000" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M11.6702 7.20292C11.2583 7.24656 10.9598 7.61586 11.0034 8.02777C11.0471 8.43968 11.4164 8.73821 11.8283 8.69457L11.6702 7.20292ZM13.5216 9.69213C13.6831 10.0736 14.1232 10.2519 14.5047 10.0904C14.8861 9.92892 15.0644 9.4888 14.9029 9.10736L13.5216 9.69213ZM16.6421 15.0869C16.349 14.7943 15.8741 14.7947 15.5815 15.0879C15.2888 15.381 15.2893 15.8559 15.5824 16.1485L16.6421 15.0869ZM18.9704 19.5305C19.2636 19.8232 19.7384 19.8228 20.0311 19.5296C20.3237 19.2364 20.3233 18.7616 20.0301 18.4689L18.9704 19.5305ZM11.8283 8.69457C12.5508 8.61801 13.2384 9.02306 13.5216 9.69213L14.9029 9.10736C14.3622 7.83005 13.0496 7.05676 11.6702 7.20292L11.8283 8.69457ZM15.5824 16.1485L18.9704 19.5305L20.0301 18.4689L16.6421 15.0869L15.5824 16.1485Z"
                                    fill="#000000" />
                            </svg>
                        </button>
                    </form>
                </div>

                <div class="nav-actions">
                    @auth
                        <div class="user-profile dropdown">
                            <button type="button" class="dropdown-toggle" onclick="toggleDropdown(this)">
                                @if(Auth::user()->profile_photo)
                                    <img src="{{ asset('storage/profile_photos/' . Auth::user()->profile_photo) ?? asset('./lib/default_media/default.jpg')}}" alt="Profile" class="profile-photo">
                                @else
                                    <img src="{{ asset('lib/default_media/default.jpg') }}" alt="Default Profile" class="profile-photo">
                                @endif
                                <span class="user-name">{{ Auth::user()->name }}</span>
                            </button>
                            <div class="dropdown-menu">
                                @if (Auth::user()->role === 'admin')
                                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                    <li class="dropdown-divider"></li>
                                @else
                                    <li><a href="{{ route('user.profile') }}">Profil Saya</a></li>
                                    <li><a href="{{ route('user.comments') }}">Komentar Saya</a></li>
                                    <li><a href="{{ route('user.bookmarks') }}">Bookmark</a></li>
                                    <li class="dropdown-divider"></li>
                                @endif
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-btn">Logout</button>
                                    </form>
                                </li>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn-login">Masuk</a>
                    @endauth

                    <button class="menu-toggle" aria-label="Toggle Menu">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" width="50px" height="50px">
                            <path
                                d="M 0 7.5 L 0 12.5 L 50 12.5 L 50 7.5 Z M 0 22.5 L 0 27.5 L 50 27.5 L 50 22.5 Z M 0 37.5 L 0 42.5 L 50 42.5 L 50 37.5 Z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <nav class="secondary-nav">
        <div class="container">
            <ul class="nav-menu">
                <li><a href="{{ route('berita.index') }}"
                        class="{{ request()->routeIs('berita.index') ? 'active' : '' }}">Beranda</a></li>
                <li><a href="https://pmb.itesa.ac.id">PMB</a></li>
                <li><a href="#">Penelitian</a></li>
                <li><a href="#">Kerjasama</a></li>
                <li><a href="#">Inovasi</a></li>
                <li><a href="#">Kontak</a></li>
                @if(!Auth::user())
                <li><a href="{{ route('login') }}">login</a></li>
                @endif
            </ul>
        </div>
    </nav>
</header>
