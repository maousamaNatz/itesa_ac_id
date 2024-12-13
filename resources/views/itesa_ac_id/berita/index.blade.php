@extends('indexberita')

@section('title', 'Berita')

@section('content')
    <div class="main-grid">
        <!-- Featured Section -->
        <div class="content-area">
            <section class="featured-section">
                @if ($topArticle)
                    <article class="feature-card primary">
                        <a href="{{ route('berita.show', $topArticle->slug) }}" class="article-link">
                            <div class="feature-image">
                                <span class="category-badge">{{ $topArticle->category->name }}</span>
                                <img src="{{ asset('storage/' . $topArticle->thumbnail) }}" alt="{{ $topArticle->title }}"
                                    loading="lazy">
                                <div class="feature-overlay"></div>
                            </div>
                            <div class="feature-content">
                                <h1>{{ $topArticle->title }}</h1>
                                <p>{!! Str::limit(strip_tags($topArticle->content), 150, '...') !!}</p>
                                <div class="meta-info">
                                    <span class="author"><i class="fas fa-user"></i> {{ $topArticle->author->name }}</span>
                                    <span class="date"><i class="fas fa-calendar"></i>
                                        {{ $topArticle->published_at->format('d M Y') }}</span>
                                    <span class="views"><i class="fas fa-eye"></i> {{ number_format($topArticle->views) }}
                                        views</span>
                                    <span class="trending"><i class="fas fa-chart-line"></i> Trending #1</span>
                                </div>
                            </div>
                        </a>
                    </article>

                    <div class="sub-features">
                        @foreach ($trendingArticles as $index => $article)
                            <article class="feature-card secondary">
                                <a href="{{ route('berita.show', $article->slug) }}" class="article-link">
                                    <div class="feature-image">
                                        <span class="category-badge">{{ $article->category->name }}</span>
                                        <img src="{{ asset('storage/' . $article->thumbnail) }}"
                                            alt="{{ $article->title }}" loading="lazy">
                                    </div>
                                    <div class="feature-content">
                                        <h2>{{ $article->title }}</h2>
                                        <div class="meta-info">
                                            <span class="date">{{ $article->published_at->format('d M Y') }}</span>
                                            <span class="views">{{ number_format($article->views) }} views</span>
                                            <span class="trending"><i class="fas fa-chart-line"></i> Trending
                                                #{{ $index + 2 }}</span>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>

            <!-- Latest News Section -->
            <section class="latest-news">
                <h2 class="section-title">Berita Terbaru</h2>
                <div class="news-grid">
                    @foreach ($articles as $article)
                        <article class="news-card">
                            <a href="{{ route('berita.show', $article->slug) }}"
                                style="text-decoration: none; color: inherit;">
                                <div class="news-image">
                                    <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="{{ $article->title }}"
                                        loading="lazy">
                                    <span class="category-badge">{{ $article->category->name }}</span>
                                </div>
                                <div class="news-content">
                                    <h3>{{ $article->title }}</h3>
                                    <p>{!! Str::limit(strip_tags($article->content), 100, '...') !!}</p>
                                    <div class="meta-info">
                                        <span><i class="fas fa-calendar"></i>
                                            {{ $article->published_at->format('d M Y') }}</span>
                                        <span><i class="fas fa-eye"></i> {{ number_format($article->views) }} views</span>
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            </section>

            <!-- Announcement Section -->
            <section class="announcement-section">
                <h2 class="section-title">Pengumuman</h2>
                <div class="announcement-list">
                    @foreach ($agendas as $agenda)
                        <article class="announcement-card">
                            <div class="announcement-date">
                                <span class="day">{{ $agenda->start_date->format('d') }}</span>
                                <span class="month">{{ $agenda->start_date->format('M') }}</span>
                            </div>
                            <div class="announcement-content">
                                <h3>{{ $agenda->title }}</h3>
                                <p>{{ $agenda->description }}</p>
                                <a href="{{ route('berita.agendaShow') }}" class="read-more">Selengkapnya</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        </div>

        <!-- Sidebar -->
        <aside class="sticky-sidebar">
            <!-- Popular News Widget -->
            <section class="widget popular-news">
                <h3 class="widget-title">Berita Populer</h3>
                <div class="popular-list">
                    @foreach ($popularArticles as $popularArticle)
                        <a href="{{ route('berita.show', $popularArticle->slug) }}" class="popular-item"
                            style="text-decoration: none; color: inherit;">
                            <span class="number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <div class="popular-content">
                                <h4>{{ $popularArticle->title }}</h4>
                                <span class="views">{{ number_format($popularArticle->views) }} views</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>

            <!-- Events Widget -->
            <section class="widget upcoming-events">
                <h3 class="widget-title">Agenda Kampus</h3>
                <div class="events-list">
                    @foreach ($agendas as $agenda)
                        <article class="event-item">
                            <div class="event-date">
                                <span class="day">{{ $agenda->start_date->format('d') }}</span>
                                <span class="month">{{ $agenda->start_date->format('M') }}</span>
                            </div>
                            <div class="event-content">
                                <h4>{{ $agenda->title }}</h4>
                                <span class="location"><i class="fas fa-map-marker-alt"></i> {{ $agenda->location }}</span>
                            </div>
                        </article>
                    @endforeach
                    <!-- More events... -->
                </div>
            </section>

            <!-- Categories Widget -->
            <section class="widget categories">
                <h3 class="widget-title">Kategori</h3>
                <ul class="category-list">
                    @foreach ($categories as $category)
                        <li><a href="{{ route('berita.category', $category->slug) }}">{{ $category->name }}
                                <span>{{ $category->articles->count() }}</span></a></li>
                    @endforeach
                </ul>
                </ul>
            </section>
        </aside>
    </div>
@endsection
