@extends('indexberita')

@section('content')
<div class="main-grid">
    <!-- Featured Section -->
    <div class="content-area">
        @if(isset($query))
            <div class="content-result-search">
                <h1>Hasil Pencarian</h1>
                <p>Hasil pencarian untuk: "{{ $query }}"</p>
            </div>
        @elseif(isset($category))
            <div class="content-result-search">
                <h1>Kategori: {{ $category->name }}</h1>
            </div>
        @elseif(isset($archive))
            <div class="content-result-search">
                <h1>Arsip: {{ $archive['month_name'] }} {{ $archive['year'] }}</h1>
            </div>
        @endif

        @if($articles->count() > 0)
            @foreach($articles as $article)
                <div class="content-result-search-item">
                    <div class="content-result-search-item-image">
                        @if($article->thumbnail)
                            <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="{{ $article->title }}">
                        @else
                            <img src="{{ asset('images/default-thumbnail.jpg') }}" alt="Default thumbnail">
                        @endif
                    </div>
                    <div class="content-result-search-item-content">
                        <h2>{{ $article->title }}</h2>
                        <p>{{ Str::limit(strip_tags($article->content), 200) }}</p>
                        <a href="{{ route('berita.show', $article->slug) }}" class="read-more">Selengkapnya</a>
                    </div>
                </div>
            @endforeach

            {{ $articles->links() }}
        @else
            <div class="no-results">
                <p>Tidak ada artikel yang ditemukan.</p>
            </div>
        @endif
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

        <!-- Categories Widget -->
        <section class="widget categories">
            <h3 class="widget-title">Kategori</h3>
            <ul class="category-list">
                @foreach($categories as $cat)
                    <li>
                        <a href="{{ route('berita.category', $cat->slug) }}"
                           class="{{ isset($category) && $category->id == $cat->id ? 'active' : '' }}">
                            {{ $cat->name }}
                            <span>({{ $cat->articles->count() }})</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>

        <!-- Archives Widget -->
        @if(isset($archives) && $archives->count() > 0)
            <section class="widget archives">
                <h3 class="widget-title">Arsip</h3>
                <ul class="archive-list">
                    @foreach($archives as $archive)
                        <li>
                            <a href="{{ route('berita.archive', ['year' => $archive['year'], 'month' => $archive['month']]) }}">
                                {{ Carbon\Carbon::create()->month($archive['month'])->translatedFormat('F') }} {{ $archive['year'] }}
                                <span>({{ $archive['total'] }})</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif
    </aside>
</div>
@endsection

