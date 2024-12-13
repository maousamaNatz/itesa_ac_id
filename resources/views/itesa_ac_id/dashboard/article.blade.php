@extends('indexdash')

@section('title', 'Artikel')

@section('content')
    <!-- Breadcrumb -->
    <nav class="bg-white p-4 mb-6 rounded-lg shadow-sm">
        <ol class="flex items-center space-x-2 text-gray-600">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="hover:text-[#D91656]">Beranda</a>
            </li>
            <li>
                <span class="mx-2">/</span>
            </li>
            <li class="text-[#a10d05]">Artikel</li>
        </ol>
    </nav>

    <!-- Content Artikel -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">Kelola Artikel</h2>
            <a href="{{ route('admin.article.create') }}"
                class="px-4 py-2 bg-[#a10d05] text-white rounded-lg hover:bg-[#8f0b04] flex items-center gap-2">
                <i class="fas fa-plus"></i>
                Tambah Artikel
            </a>
        </div>

        <!-- Filter Section -->
        <div class="flex flex-wrap gap-4 mb-6">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select name="category"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-[#a10d05] focus:border-[#a10d05]">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-[#a10d05] focus:border-[#a10d05]">
                    <option value="">Semua Status</option>
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                    <option value="review">Review</option>
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                <input type="text" name="search" placeholder="Cari artikel..."
                    class="w-full border rounded-lg px-3 py-2 focus:ring-[#a10d05] focus:border-[#a10d05]">
            </div>
        </div>

        <!-- Table Section -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penulis
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($articles as $article)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center ">
                                    <img src="{{ $article->thumbnail ? asset('storage/' . $article->thumbnail) : asset('lib/default_media/no-image.png') }}"
                                        alt="{{ $article->title }}" class="h-10 w-10 rounded-lg object-cover mr-3">
                                    <div class="ml-4">

                                        <div class="text-sm font-medium jdl-items text-gray-900">
                                            <span>

                                                {{ $article->title }}
                                            </span>
                                        </div>
                                        <div
                                            class="w-max-[200px] flex-nowrap flex gap-2 overflow-hidden whitespace-nowrap text-nowrap elipsis">
                                            @foreach ($article->tags as $tag)
                                                <div class="text-sm text-gray-500">#{{ $tag->name }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if ($article->category)
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-[#a10d05]">
                                        {{ $article->category->name }}
                                    </span>
                                @endif
                                @if ($article->categories->isNotEmpty())
                                    <di v class="mt-1 space-x-1">
                                        @foreach ($article->categories as $category)
                                            @if ($category->id !== $article->category_id)
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    {{ $category->name }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </di>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $article->author->username }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $article->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($article->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($article->created_at)->locale('id')->isoFormat('D MMMM Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('admin.article.edit', $article->id) }}"
                                        class="text-[#a10d05] hover:text-[#D91656]" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

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

                                    <form action="{{ route('admin.article.destroy', $article->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
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

        <!-- Pagination -->
        @if ($articles->hasPages())
            <div class="flex items-center justify-between mt-6">
                <div class="text-sm text-gray-500">
                    Menampilkan {{ $articles->firstItem() }} - {{ $articles->lastItem() }} dari {{ $articles->total() }}
                    artikel
                </div>
                {{ $articles->links() }}
            </div>
        @endif
    </div>
    <script src="{{ asset('lib/js/filter.js') }}"></script>
    <script src="{{ asset('lib/js/filterarticle.js') }}"></script>
@endsection
