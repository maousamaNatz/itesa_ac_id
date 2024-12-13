<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="{{ Str::limit(strip_tags($article->content), 160) }}" />
    <meta property="og:title" content="{{ $article->title }}" />
    <meta property="og:description" content="{{ Str::limit(strip_tags($article->content), 160) }}" />
    <meta property="og:image" content="{{ asset('storage/' . $article->thumbnail) }}" />
    <meta property="og:url" content="{{ request()->url() }}" />

    <title>{{ $article->title }} - Portal Berita ITESA</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('lib/default_media/logos.png') }}">
    <link rel="stylesheet" href="{{ asset('lib/css/show.css') }}" />
    <link rel="stylesheet" href="{{ asset('lib/css/berita.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>

<body>

    @include('components.navbarberita')
    <main class="container">
        <header class="content">
            <div>
                <article class="tag-title-main">
                    <div>
                        <h3>
                            {{ $article->title }}
                        </h3>
                        <h5>upload at {{ $article->created_at->format('d F Y') }}</h5>
                    </div>
                </article>
                <article class="profile">
                    <div>
                        <figure class="profile-img">
                            <img src="{{ asset('lib/default_media/logos.png') }}" alt="" />
                        </figure>
                        <article class="profile-name">
                            <h4><b>itesa</b> Muhammdiyah Semarang</h4>
                            <h5>publisher
                                {{ $article->author->username }}
                            </h5>
                        </article>
                    </div>
                </article>
                <main class="content-all">
                    <main class="content-main">
                        <div>
                            <figure class="content-main-img">
                                <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="" />
                            </figure>
                            <div class="content-items-details">
                                <ul class="content-items-details-list">
                                    <li class="content-items-details-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ $article->created_at->format('d F Y') }}
                                    </li>
                                    <li class="content-items-details-item">
                                        <i class="fas fa-eye"></i>
                                        {{ $article->views }}
                                    </li>
                                </ul>
                            </div>
                            <main class="content-main-desc">
                                <div>
                                    {!! $article->content !!}
                                </div>
                            </main>
                            <ul class="media-items">
                                <li class="media-item">
                                    <i class="fab fa-facebook-f"></i>
                                    <h4>facebook</h4>
                                </li>
                                <li class="media-item">
                                    <i class="fab fa-instagram"></i>
                                    <h4>instagram</h4>
                                </li>
                                <li class="media-item">
                                    <i class="fab fa-twitter"></i>
                                    <h4>twitter</h4>
                                </li>
                                <li class="media-item">
                                    <i class="fab fa-share-alt"></i>
                                    <h4>share</h4>
                                </li>
                            </ul>
                            <ul class="tags">
                                <li class="tag">
                                    <h4>tag :</h4>
                                </li>
                                @foreach ($article->tags as $tags)
                                    <li class="tag-item">
                                        <a
                                            href="{{ route('berita.category', $tags->slug) }}">#{{ $tags->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="popular-categories-section">
                            <h3>Kategori Populer</h3>
                            @foreach ($popularCategories as $category)
                                <div class="category-block">
                                    <h4>{{ $category->name }} ({{ $category->articles_count }} artikel)</h4>
                                    <div class="category-articles">
                                        @foreach ($category->popular_articles as $popularArticle)
                                            <article class="category-article">
                                                <a href="{{ route('berita.show', $popularArticle->slug) }}">
                                                    <h5>{{ $popularArticle->title }}</h5>
                                                    <span
                                                        class="article-date">{{ $popularArticle->created_at->format('d F Y') }}</span>
                                                </a>
                                            </article>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="weekly-top-section">
                            <h3>
                                <span>

                                </span>
                                Artikel Populer Minggu Ini
                            </h3>
                            <div class="weekly-articles">
                                @foreach ($weeklyTopArticles as $weeklyArticle)
                                    <article class="weekly-article">
                                        <a href="{{ route('berita.show', $weeklyArticle->slug) }}">
                                            <div class="article-thumbnail">
                                                <img src="{{ asset('storage/' . $weeklyArticle->thumbnail) }}"
                                                    alt="{{ $weeklyArticle->title }}" loading="lazy">
                                            </div>
                                            <div class="article-info">
                                                <h4>{{ $weeklyArticle->title }}</h4>
                                                <span
                                                    class="article-date">{{ $weeklyArticle->created_at->format('d F Y') }}</span>
                                                <span class="article-views"><i class="fas fa-eye"></i>
                                                    {{ $weeklyArticle->views }}</span>
                                            </div>
                                        </a>
                                    </article>
                                @endforeach
                            </div>
                        </div>

                    </main>
                    <aside class="content-another">
                        <div>
                            <main class="content-another-main">
                                <figure class="card-another-tag">
                                    <article>
                                        <h3>artikel terbaru</h3>
                                    </article>
                                </figure>
                                <header class="another-random">
                                    @foreach ($latestArticles as $article)
                                        <figure class="card-random">
                                            <a href="{{ route('berita.show', $article->slug) }}"
                                                class="card-random-link">
                                                <div class="random-img">
                                                    <img src="{{ asset('storage/' . $article->thumbnail) }}"
                                                        alt="{{ $article->title }}" loading="lazy" />
                                                </div>
                                                <article class="random-txt">
                                                    <h6>{{ $article->created_at->format('d F Y') }}</h6>
                                                    <h4>{{ $article->title }}</h4>
                                                </article>
                                            </a>
                                        </figure>
                                    @endforeach
                                </header>
                            </main>
                        </div>

                        <div>
                            <main class="content-another-main">
                                <figure class="card-another-tag">
                                    <article>
                                        <h3>rekomendasi untuk anda</h3>
                                    </article>
                                </figure>
                                <header class="another-random">
                                    @foreach ($recommendedArticles as $article)
                                        <figure class="card-random">
                                            <a href="{{ route('berita.show', $article->slug) }}"
                                                class="card-random-link">
                                                <div class="random-img">
                                                    <img src="{{ asset('storage/' . $article->thumbnail) }}"
                                                        alt="{{ $article->title }}" loading="lazy" />
                                                </div>
                                                <article class="random-txt">
                                                    <h6>{{ $article->created_at->format('d F Y') }}</h6>
                                                    <h4>{{ $article->title }}</h4>
                                                </article>
                                            </a>
                                        </figure>
                                    @endforeach
                                </header>
                            </main>
                        </div>

                        <div>
                            <main class="content-another-main">
                                <figure class="card-another-tag">
                                    <article>
                                        <h3>kategori</h3>
                                    </article>
                                </figure>
                                <div class="categories-list">
                                    @foreach ($allCategories as $category)
                                        <a href="{{ route('berita.category', $category->slug) }}"
                                           class="category-item {{ request()->segment(3) == $category->slug ? 'active' : '' }}">
                                            <span class="category-name">{{ $category->name }}</span>
                                            <span class="category-count">({{ $category->articles_count }})</span>
                                        </a>
                                    @endforeach
                                </div>
                            </main>
                        </div>

                        <div>
                            <main class="content-another-main">
                                <figure class="card-another-tag">
                                    <article>
                                        <h3>artikel terkait</h3>
                                    </article>
                                </figure>
                                <header class="another-random related-articles">
                                    @foreach ($relatedArticles as $article)
                                        <figure class="card-random">
                                            <a href="{{ route('berita.show', $article->slug) }}"
                                                class="card-random-link">
                                                <div class="random-img">
                                                    <img src="{{ asset('storage/' . $article->thumbnail) }}"
                                                        alt="{{ $article->title }}" loading="lazy" />
                                                </div>
                                                <article class="random-txt">
                                                    <h6>{{ $article->created_at->format('d F Y') }}</h6>
                                                    <h4>{{ $article->title }}</h4>
                                                </article>
                                            </a>
                                        </figure>
                                    @endforeach
                                </header>
                            </main>
                        </div>

                    </aside>
                </main>
            </div>
        </header>
    </main>

    @include('components.footerberita')

    <script>
        let lastScrollTop = 0;
        const navbar = document.querySelector('.navbar-berita');
        const navbarHeight = navbar.offsetHeight;

        // Tambahkan class untuk transisi smooth
        navbar.style.transition = 'transform 0.3s ease-in-out';

        window.addEventListener('scroll', function() {
            let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

            // Scroll ke bawah
            if (currentScroll > lastScrollTop && currentScroll > navbarHeight) {
                // Sembunyikan navbar
                navbar.style.transform = 'translateY(-100%)';
            }
            // Scroll ke atas
            else {
                // Tampilkan navbar
                navbar.style.transform = 'translateY(0)';
            }

            lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
        }, false);

        // Tambahkan class active untuk navbar saat scroll
        window.addEventListener('scroll', function() {
            if (window.scrollY > 100) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        });
    </script>
</body>

</html>
