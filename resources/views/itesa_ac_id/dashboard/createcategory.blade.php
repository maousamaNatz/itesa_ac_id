@extends('indexdash')

@section('title', 'Tambah Kategori')

@section('content')
<nav class="bg-white p-4 mb-6 rounded-lg shadow-sm">
    <ol class="flex items-center space-x-2 text-gray-600">
        <li><a href="{{ route('admin.dashboard') }}" class="hover:text-[#D91656]">Beranda</a></li>
        <li><span class="mx-2">/</span></li>
        <li><a href="{{ route('admin.category') }}" class="hover:text-[#D91656]">Kategori</a></li>
        <li><span class="mx-2">/</span></li>
        <li class="text-[#a10d05]">Tambah Kategori</li>
    </ol>
</nav>

<div class="bg-white rounded-lg shadow-sm p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Tambah Kategori Baru</h2>

    <form action="{{ route('admin.storecategory') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-[#a10d05] focus:border-[#a10d05] @error('name') border-red-500 @enderror"
                       required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description"
                          rows="4"
                          class="w-full border rounded-lg px-4 py-2 focus:ring-[#a10d05] focus:border-[#a10d05] @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Gambar</label>
                <input type="file"
                       name="image"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-[#a10d05] focus:border-[#a10d05] @error('image') border-red-500 @enderror">
                @error('image')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.category') }}"
                   class="px-6 py-2 border rounded-lg hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-[#a10d05] text-white rounded-lg hover:bg-[#8f0b04]">
                    Simpan Kategori
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
