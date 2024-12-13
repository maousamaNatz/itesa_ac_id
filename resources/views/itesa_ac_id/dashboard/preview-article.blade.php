@extends('indexdash')

@section('title', 'Preview Draft Article')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <!-- Preview Banner -->
    <div class="bg-yellow-100 border-l-4 border-yellow-500 p-4 mb-6">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
            <p class="text-yellow-700">
                Preview Mode: Artikel ini masih berstatus <strong>{{ ucfirst($article->status) }}</strong>
            </p>
        </div>
    </div>

    <!-- Article Content -->
    <div class="article-preview">
        <!-- Article Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold mb-2">{{ $article->title }}</h1>
            <div class="flex items-center text-gray-600 text-sm">
                <span class="mr-4">
                    <i class="fas fa-user mr-1"></i>
                    {{ $article->author->name }}
                </span>
                <span class="mr-4">
                    <i class="fas fa-calendar mr-1"></i>
                    {{ $article->created_at->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </span>
                <span>
                    <i class="fas fa-folder mr-1"></i>
                    {{ $article->category->name }}
                </span>
            </div>
        </div>

        <!-- Thumbnail -->
        @if($article->thumbnail)
        <div class="mb-6">
            <img src="{{ asset('storage/' . $article->thumbnail) }}"
                 alt="{{ $article->title }}"
                 class="w-full rounded-lg shadow-sm">
        </div>
        @endif

        <!-- Content -->
        <div class="prose max-w-none mb-8">
            {!! $article->content !!}
        </div>

        <!-- Meta Information -->
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h3 class="text-lg font-semibold mb-2">Meta Information</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Meta Title:</p>
                    <p class="font-medium">{{ $article->meta_title }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Meta Description:</p>
                    <p class="font-medium">{{ $article->meta_description }}</p>
                </div>
            </div>
        </div>

        <!-- Tags -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Tags</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($article->tags as $tag)
                    <span style="padding-top: 0.1em; padding-bottom: 0.1rem" class="text-xs px-3 bg-gray-200 rounded-full">
                        #{{ $tag->name }}
                    </span>
                @endforeach
            </div>
        </div>

        <!-- Categories -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Categories</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($article->categories as $category)
                    <span style="padding-top: 0.1em; padding-bottom: 0.1rem" class="text-xs px-3 bg-gray-200 rounded-full
                        {{ $category->id === $article->category_id ? 'bg-red-100 text-red-800' : 'text-gray-700' }}">
                        {{ $category->name }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-end space-x-4 mt-6 pt-6 border-t">
        <a href="{{ route('admin.article.edit', $article->id) }}"
           class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
            <i class="fas fa-edit mr-2"></i>
            Edit Article
        </a>
        <a href="{{ route('admin.article.index') }}"
           class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to List
        </a>
    </div>
</div>
@endsection
