@extends('indexdash')

@section('title', 'Kategori')

@section('content')
<nav class="bg-white p-4 mb-6 rounded-lg shadow-sm">
    <ol class="flex items-center space-x-2 text-gray-600">
        <li>
            <a href="{{ route('admin.dashboard') }}" class="hover:text-[#D91656]">Beranda</a>
        </li>
        <li>
            <span class="mx-2">/</span>
        </li>
        <li class="text-[#a10d05]">Kategori</li>
    </ol>
</nav>

<!-- Content Kategori -->
<div class="bg-white rounded-lg shadow-sm p-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Kelola Kategori</h2>
        <a href="{{ route('admin.category.create') }}" class="bg-[#a10d05] text-white px-4 py-2 rounded-lg hover:bg-[#8f0b04] flex items-center gap-2">
            <i class="fas fa-plus"></i>
            Tambah Kategori
        </a>
    </div>

    <!-- Search & Filter Section -->
    <div class="flex flex-wrap gap-4 mb-6">
        <div class="flex-1 min-w-[200px]">
            <input type="text"
                   placeholder="Cari kategori..."
                   class="w-full border rounded-lg px-4 py-2 focus:ring-[#a10d05] focus:border-[#a10d05]">
        </div>
        <div class="flex-1 min-w-[200px]">
            <select class="w-full border rounded-lg px-4 py-2 focus:ring-[#a10d05] focus:border-[#a10d05]">
                <option value="">Urutkan berdasarkan</option>
                <option value="name_asc">Nama (A-Z)</option>
                <option value="name_desc">Nama (Z-A)</option>
                <option value="newest">Terbaru</option>
                <option value="oldest">Terlama</option>
            </select>
        </div>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Artikel</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Dibuat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($categories as $category)
                <tr>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $category->slug }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $category->articles_count }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $category->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.category.edit', $category->id) }}" class="text-[#a10d05] hover:text-[#D91656]" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.category.destroy', $category->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada kategori yang ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($categories->hasPages())
    <div class="flex items-center justify-between mt-6">
        <div class="text-sm text-gray-500">
            Menampilkan {{ $categories->firstItem() }} - {{ $categories->lastItem() }} dari {{ $categories->total() }} kategori
        </div>
        <div class="flex items-center space-x-2">
            {{ $categories->links() }}
        </div>
    </div>
    @endif
</div>

<script src="{{ asset('lib/js/filter.js') }}"></script>
<script src="{{ asset('lib/js/filterarticle.js') }}"></script>
@endsection

