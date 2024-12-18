@extends('indexdash')

@section('title', 'Tambah Artikel')

@section('content')
<style>
    .top-100 {top: 100%}
    .bottom-100 {bottom: 100%}
    .max-h-select {
        max-height: 300px;
    }
    .border-[#a10d05] {
    border-color: #a10d05;
}

#dropZone.border-[#a10d05] {
    border-style: solid;
}

#thumbnailPreview {
    max-height: 100%;
    width: auto;
    margin: auto;
}
</style>

<nav class="bg-white p-4 mb-6 rounded-lg shadow-sm">
    <ol class="flex items-center space-x-2 text-gray-600">
        <li><a href="{{ route('admin.dashboard') }}" class="hover:text-[#D91656]">Beranda</a></li>
        <li><span class="mx-2">/</span></li>
        <li><a href="{{ route('admin.article.index') }}" class="hover:text-[#D91656]">Artikel</a></li>
        <li><span class="mx-2">/</span></li>
        <li class="text-[#a10d05]">Tambah Artikel</li>
    </ol>
</nav>

<div class="bg-white rounded-lg shadow-sm p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Tambah Artikel Baru</h2>

    <form method="POST" action="{{ route('admin.article.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Judul Artikel</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-[#a10d05] focus:border-[#a10d05] @error('title') border-red-500 @enderror"
                    placeholder="Masukkan judul artikel..." required>
                <input type="hidden" name="slug" id="slug" value="{{ old('slug') }}">
                @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="lg:col-span-1" id="categorySelector">
                <label class="block text-sm font-medium text-gray-700">Kategori</label>

                <div class="w-full flex flex-col items-center mx-auto">
                    <div class="w-full">
                        <div class="flex flex-col items-center relative w-full">
                            <div class="w-full">
                                <div class="my-2 p-1 flex border border-gray-200 bg-white rounded w-full">
                                    <div class="flex flex-auto flex-wrap w-full overflow-x-auto items-center justify-start">
                                        <div id="selected_category_tags" class="flex flex-wrap gap-2">
                                            <!-- Category tags will be injected here -->
                                        </div>
                                    </div>
                                    <div class="text-gray-300 w-8 py-1 pl-2 pr-1 border-l flex items-center border-gray-200">
                                        <button type="button" id="toggleDropdown"
                                                class="cursor-pointer w-6 h-6 text-gray-600 outline-none focus:outline-none">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 width="100%"
                                                 height="100%"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor"
                                                 stroke-width="2"
                                                 stroke-linecap="round"
                                                 stroke-linejoin="round"
                                                 class="feather feather-chevron-up w-4 h-4">
                                                <polyline points="18 15 12 9 6 15"></polyline>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div id="categoryDropdown" class="absolute shadow top-100 bg-white z-40 w-full left-0 rounded max-h-select overflow-y-auto hidden">
                                <div class="flex flex-col w-full">
                                    <input type="text"
                                           id="categorySearch"
                                           placeholder="Cari kategori..."
                                           class="bg-transparent p-2 border-b border-gray-100 outline-none">
                                    <div class="category-list">
                                        @foreach($categories as $category)
                                            <div class="cursor-pointer w-full border-gray-100 rounded-t border-b hover:bg-teal-100 category-item"
                                                 data-value="{{ $category->id }}"
                                                 data-name="{{ $category->name }}">
                                                <div class="flex w-full items-center p-2 pl-2 border-transparent border-l-2 relative">
                                                    <div class="mx-2 leading-6">{{ $category->name }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="selected_categories">
                    <!-- Hidden inputs will be injected here -->
                </div>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Thumbnail</label>
            <div class="flex items-center justify-center w-full">
                <div id="dropZone" class="flex flex-col w-full  md:h-72 lg:h-80 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer hover:bg-gray-50 transition-all duration-200 relative overflow-hidden">
                    <!-- Preview Container -->
                    <div id="previewContainer" class="hidden w-full h-full bg-gray-100">
                        <div class="relative w-full h-full flex items-center justify-center">
                            <img id="thumbnailPreview" class="max-w-full max-h-full object-contain" src="" alt="Preview">
                            <!-- Loading Indicator -->
                            <div id="loadingIndicator" class="hidden absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#a10d05]"></div>
                            </div>
                            <!-- Tombol hapus -->
                            <button type="button"
                                    id="removeImage"
                                    class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-2 hover:bg-red-600 transition-colors duration-200 shadow-lg opacity-90 hover:opacity-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <!-- Upload Container -->
                    <div id="uploadContainer" class="flex flex-col items-center justify-center h-full p-6 text-center">
                        <div class="mb-3">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                      stroke-width="2"
                                      stroke-linecap="round"
                                      stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm text-gray-500">
                                <span class="font-semibold">Klik untuk upload</span> atau drag and drop
                            </p>
                            <p class="text-xs text-gray-500">PNG, JPG, atau JPEG (max 2MB)</p>
                        </div>
                    </div>
                    <input type="file"
                           id="thumbnailInput"
                           name="thumbnail"
                           class="hidden"
                           accept="image/png,image/jpeg,image/jpg">
                </div>
            </div>
            <!-- Error Container -->
            <div class="mt-2 flex items-center justify-between">
                <div class="flex items-center space-x-2 text-xs text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Rekomendasi: 1200 x 630 piksel</span>
                </div>
                <p id="errorMessage" class="hidden text-xs text-red-500 font-medium"></p>
            </div>
        </div>

        <!-- Editor TinyMCE -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Konten Artikel</label>
            <textarea id="editor" name="content" class="@error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
            @error('content')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Meta Tags -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                <input type="text" name="meta_title" value="{{ old('meta_title') }}"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-[#a10d05] focus:border-[#a10d05] @error('meta_title') border-red-500 @enderror"
                    placeholder="Meta title untuk SEO...">
                @error('meta_title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                <input type="text" name="meta_keyword" value="{{ old('meta_keyword') }}"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-[#a10d05] focus:border-[#a10d05] @error('meta_keyword') border-red-500 @enderror"
                    placeholder="Kata kunci dipisahkan dengan koma...">
                @error('meta_keyword')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
            <textarea name="meta_description"
                class="w-full border rounded-lg px-4 py-2 focus:ring-[#a10d05] focus:border-[#a10d05] @error('meta_description') border-red-500 @enderror"
                rows="3" placeholder="Deskripsi singkat untuk SEO...">{{ old('meta_description') }}</textarea>
            @error('meta_description')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
            <div class="relative">
                <input type="text"
                       id="tagInput"
                       name="tags"
                       value="{{ old('tags') }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-[#a10d05] focus:border-[#a10d05]"
                       placeholder="Masukkan tags (pisahkan dengan koma)">
                <div id="tagPreview" class="mt-2 flex flex-wrap gap-2">
                    <!-- Tag preview akan muncul di sini -->
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-1">Contoh: teknologi, berita, kampus</p>
            @error('tags')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tambahkan field status sebelum tombol submit -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Status Artikel</label>
            <select name="status" class="w-full border rounded-lg px-4 py-2 focus:ring-[#a10d05] focus:border-[#a10d05]">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
            </select>
            @error('status')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tombol Aksi -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.article.index') }}"
               class="px-6 py-2 border rounded-lg hover:bg-gray-50">
                Batal
            </a>
            <button type="submit" class="px-6 py-2 bg-[#a10d05] text-white rounded-lg hover:bg-[#8f0b04]">
                Simpan Artikel
            </button>
        </div>
    </form>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cek error validasi dari server
        @if ($errors->any())
            showNotification(
                'error',
                'Error!',
                'Terdapat kesalahan pada form. Silakan periksa kembali.'
            );
        @endif

        // Cek notifikasi dari session
        @if (session()->has('notification'))
            const notification = @json(session('notification'));
            showNotification(
                notification.type,
                notification.title,
                notification.message
            );
        @endif

        // Script untuk slug
        document.getElementById('title').addEventListener('input', function() {
            let slug = this.value.toLowerCase()
                .replace(/[^a-z0-9-]/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');
            document.getElementById('slug').value = slug;
        });

        // Tag Input Handler
        const tagInput = document.getElementById('tagInput');
        const tagPreview = document.getElementById('tagPreview');

        function createTagElement(tagName) {
            const tag = document.createElement('span');
            tag.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#a10d05] text-white';
            tag.innerHTML = `
                ${tagName}
                <button type="button" class="ml-1 inline-flex items-center justify-center" onclick="removeTag(this)">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            `;
            return tag;
        }

        function updateTagPreview() {
            tagPreview.innerHTML = '';
            const tags = tagInput.value.split(',').filter(tag => tag.trim() !== '');

            tags.forEach(tag => {
                const trimmedTag = tag.trim();
                if (trimmedTag) {
                    tagPreview.appendChild(createTagElement(trimmedTag));
                }
            });
        }

        function removeTag(button) {
            const tagElement = button.parentElement;
            const tagText = tagElement.textContent.trim();
            const currentTags = tagInput.value.split(',').map(t => t.trim());
            const updatedTags = currentTags.filter(t => t !== tagText);
            tagInput.value = updatedTags.join(', ');
            updateTagPreview();
        }

        tagInput.addEventListener('input', updateTagPreview);
        tagInput.addEventListener('blur', () => {
            const tags = tagInput.value.split(',')
                .map(tag => tag.trim())
                .filter(tag => tag !== '');
            tagInput.value = tags.join(', ');
            updateTagPreview();
        });

        // Initialize tag preview if there are existing tags
        if (tagInput.value) {
            updateTagPreview();
        }
    });
</script>
@endpush
@endsection
