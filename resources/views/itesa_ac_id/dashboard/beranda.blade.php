@extends('indexdash')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-[#a10d05] rounded-lg">
                    <i class="fas fa-newspaper text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Total Artikel</h3>
                    <p class="text-2xl font-semibold">{{ $totalArticles ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-[#bf1206] rounded-lg">
                    <i class="fas fa-eye text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Total Views</h3>
                    <p class="text-2xl font-semibold">{{ $totalViews ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-[#cc2617] rounded-lg">
                    <i class="fas fa-comments text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Komentar</h3>
                    <p class="text-2xl font-semibold">{{ $totalComments ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-[#d93928] rounded-lg">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Pengguna</h3>
                    <p class="text-2xl font-semibold">{{ $totalUsers ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="p-8">
        <!-- Quick Actions -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Aksi Cepat</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.article.create') }}"
                    class="flex items-center justify-center p-4 bg-[#a10d05] text-white rounded-lg hover:bg-[#8f0b04] transition-colors">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Tulis Artikel Baru
                </a>
                <a href="{{ route('admin.category.create') }}"
                    class="flex items-center justify-center p-4 bg-[#bf1206] text-white rounded-lg hover:bg-[#a10d05] transition-colors">
                    <i class="fas fa-folder-plus mr-2"></i>
                    Tambah Kategori
                </a>
                <a href="{{ route('admin.comment.index') }}"
                    class="flex items-center justify-center p-4 bg-[#cc2617] text-white rounded-lg hover:bg-[#bf1206] transition-colors">
                    <i class="fas fa-tasks mr-2"></i>
                    Moderasi Komentar
                </a>
                {{-- <a href="{{ route('admin.analytics') }}" class="flex items-center justify-center p-4 bg-[#d93928] text-white rounded-lg hover:bg-[#cc2617] transition-colors">
                <i class="fas fa-chart-line mr-2"></i>
                Lihat Analytics
            </a> --}}
            </div>
        </div>

        <!-- Latest Articles Table -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-xl font-semibold">Daftar Artikel Terbaru</h2>
                <a href="{{ route('admin.article.index') }}"
                    class="px-4 py-2 bg-[#a10d05] text-white rounded-lg hover:bg-[#8f0b04] text-sm">
                    Lihat Semua
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Penulis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($articles as $article)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img class="h-10 w-10 rounded-lg object-cover"
                                            src="{{ $article->thumbnail ? asset('storage/' . $article->thumbnail) : asset('lib/default_media/no-image.png') }}"
                                            alt="{{ $article->title }}" width="800" height="600" loading="lazy"
                                            decoding="async">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 elipsis jdl-items">
                                                <a href="{{ route('berita.show', $article->slug) }}"
                                                    class="hover:text-[#a10d05]">
                                                    {{ $article->title }}
                                                </a>
                                            </div>
                                            <div class="w-max-[200px] flex-nowrap flex gap-2 overflow-hidden whitespace-nowrap text-nowrap elipsis">
                                                @foreach ($article->tags as $tag)
                                                    <div class="text-sm text-gray-500">#{{ $tag->name }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-[#a10d05]">
                                        {{ $article->category->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $article->author->username }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $article->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($article->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $article->created_at->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.article.edit', $article->id) }}"
                                        class="text-[#a10d05] hover:text-[#D91656] mr-3">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.article.destroy', $article->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    Tidak ada artikel
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($articles instanceof \Illuminate\Pagination\LengthAwarePaginator && $articles->hasPages())
                <div class="px-6 py-4 border-t">
                    {{ $articles->links() }}
                </div>
            @endif
        </div>
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-xl font-semibold">Komentar Terbaru</h2>
                <a href="{{ route('admin.comment.index') }}" class="text-blue-500 hover:text-blue-600">
                    Lihat Semua
                </a>
            </div>
            <div class="p-6">
                <div class="space-y-6">
                    @forelse($latestComments as $comment)
                        <div class="flex items-start space-x-4">
                            <img src="{{ $comment->user->profile_photo ? asset('storage/' . $comment->user->profile_photo) : asset('lib/default_media/default-avatar.png') }}"
                                alt="{{ $comment->user->username }}" class="w-10 h-10 rounded-full object-cover">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-medium">{{ $comment->user->username }}</h4>
                                    <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $comment->content }}
                                </p>
                                <div class="mt-2 flex space-x-4">

                                    @if ($article->status === 'draft')
                                        <a href="{{ route('admin.article.show', ['slug' => $article->slug]) }}"
                                            class="text-yellow-600 hover:text-yellow-800" title="Preview Draft">
                                            <i class="fas fa-eye-slash"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('berita.show', ['slug' => $article->slug]) }}"
                                            class="text-green-600 hover:text-green-800" title="View Published"
                                            target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                    <form action="{{ route('admin.comment.approve', $comment->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-600">
                                            <i class="fas fa-check mr-1"></i> Setujui
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.comment.destroy', $comment->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-500 hover:text-red-600"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus komentar ini?')">
                                            <i class="fas fa-trash mr-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500">
                            Tidak ada komentar terbaru
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
