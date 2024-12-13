@extends('indexdash')

@section('title', 'Edit Artikel')

@section('content')
<nav class="bg-white p-4 mb-6 rounded-lg shadow-sm">
    <ol class="flex items-center space-x-2 text-gray-600">
        <li><a href="{{ route('admin.dashboard') }}" class="hover:text-[#D91656]">Beranda</a></li>
        <li><span class="mx-2">/</span></li>
        <li><a href="{{ route('admin.article.index') }}" class="hover:text-[#D91656]">Artikel</a></li>
        <li><span class="mx-2">/</span></li>
        <li class="text-[#a10d05]">Edit Artikel</li>
    </ol>
</nav>

<div class="bg-white rounded-lg shadow-sm p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Edit Artikel</h2>

    <form method="POST" action="{{ route('admin.article.update', $article->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Judul Artikel</label>
                <input type="text" name="title" id="title" value="{{ old('title', $article->title) }}"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-[#a10d05] focus:border-[#a10d05] @error('title') border-red-500 @enderror"
                    placeholder="Masukkan judul artikel..." required>
                <input type="hidden" name="slug" id="slug" value="{{ old('slug', $article->slug) }}">
                @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="lg:col-span-1">
                <label class="block text-sm font-medium text-gray-700">Kategori</label>
                <div class="w-full flex flex-col items-center mx-auto">
                    <div class="w-full">
                        <div class="flex flex-col items-center relative w-full">
                            <div class="w-full svelte-1l8159u">
                                <div class="my-2 p-1 flex border border-gray-200 bg-white rounded svelte-1l8159u w-full">
                                    <div class="flex flex-auto flex-wrap w-full overflow-x-auto cpcb items-center justify-start">
                                        @foreach($article->categories as $category)
                                            <div class="flex justify-center items-center m-1 font-medium py-1 px-2 bg-white rounded-full text-[#a10d05] bg-[#a10d05] bg-opacity-10 border border-[#a10d05] ">
                                                <div class="text-xs font-normal leading-none max-w-full flex-initial">{{ $category->name }}</div>
                                                <div class="flex flex-auto flex-row-reverse">
                                                    <div class="cursor-pointer remove-category ml-2" data-value="{{ $category->id }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x cursor-pointer hover:text-[#a10d05] rounded-full w-4 h-4">
                                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="absolute shadow top-100 bg-white z-40 w-full lef-0 rounded max-h-select overflow-y-auto svelte-5uyqqj hidden">
                                <div class="flex flex-col w-full">
                                    <div class="cursor-pointer w-full border-gray-100 rounded-t border-b hover:bg-teal-100">
                                        <div class="flex w-full items-center p-2 pl-2 border-transparent border-l-2 relative hover:border-teal-100">
                                            <div class="w-full items-center flex">
                                                <div class="mx-2 leading-6">
                                                    <input type="text" class="w-full border-0 focus:ring-0" placeholder="Cari kategori...">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @foreach($categories as $category)
                                        <div class="cursor-pointer w-full border-gray-100 border-b hover:bg-teal-100 {{ in_array($category->id, $article->categories->pluck('id')->toArray()) ? 'bg-teal-50' : '' }}"
                                             data-value="{{ $category->id }}"
                                             data-name="{{ $category->name }}">
                                            <div class="flex w-full items-center p-2 pl-2 border-transparent border-l-2 relative hover:border-teal-100">
                                                <div class="w-full items-center flex">
                                                    <div class="mx-2 leading-6">{{ $category->name }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="selected_categories">
                    @foreach($article->categories as $category)
                        <input type="hidden" name="category_ids[]" value="{{ $category->id }}">
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mb-6" x-data="thumbnailPreview">
            <label class="block text-sm font-medium text-gray-700 mb-2">Thumbnail</label>
            <div class="flex items-center justify-center w-full">
                <label class="flex flex-col w-full h-32 border-2 border-dashed rounded-lg cursor-pointer hover:bg-gray-50">
                    <div class="relative">
                        @if($article->thumbnail)
                            <img src="{{ asset('storage/' . $article->thumbnail) }}"
                                 alt="Current thumbnail"
                                 class="absolute inset-0 w-full h-32 object-cover rounded-lg">
                        @endif
                        <div class="flex flex-col items-center justify-center pt-7">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                            <p class="text-sm text-gray-500">Klik untuk ganti gambar</p>
                        </div>
                    </div>
                    <input type="file" name="thumbnail" class="hidden" accept="image/*">
                </label>
            </div>
            <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah thumbnail</p>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Konten Artikel</label>
            <textarea id="editor" name="content" class="@error('content') border-red-500 @enderror">{{ old('content', $article->content) }}</textarea>
            @error('content')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                <input type="text" name="meta_title" value="{{ old('meta_title', $article->meta_title) }}"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-[#a10d05] focus:border-[#a10d05]"
                    placeholder="Meta title untuk SEO...">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                <input type="text" name="meta_keyword" value="{{ old('meta_keyword', $article->meta_keyword) }}"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-[#a10d05] focus:border-[#a10d05]"
                    placeholder="Kata kunci dipisahkan dengan koma...">
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
            <textarea name="meta_description"
                class="w-full border rounded-lg px-4 py-2 focus:ring-[#a10d05] focus:border-[#a10d05]"
                rows="3" placeholder="Deskripsi singkat untuk SEO...">{{ old('meta_description', $article->meta_description) }}</textarea>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Status Artikel</label>
            <select name="status" class="w-full border rounded-lg px-4 py-2 focus:ring-[#a10d05] focus:border-[#a10d05]">
                <option value="draft" {{ $article->status === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ $article->status === 'published' ? 'selected' : '' }}>Published</option>
            </select>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.article.index') }}"
               class="px-6 py-2 border rounded-lg hover:bg-gray-50">
                Batal
            </a>
            <button type="submit" class="px-6 py-2 bg-[#a10d05] text-white rounded-lg hover:bg-[#8f0b04]">
                Update Artikel
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Script untuk kategori
    document.addEventListener('DOMContentLoaded', function() {
        const multiSelect = document.querySelector('.w-full.flex.flex-col.items-center');
        const selectedContainer = multiSelect.querySelector('.flex.flex-auto.flex-wrap');
        const dropdownButton = multiSelect.querySelector('.cursor-pointer');
        const dropdown = multiSelect.querySelector('.absolute.shadow');
        const searchInput = dropdown.querySelector('input');
        const categoryItems = dropdown.querySelectorAll('.cursor-pointer[data-value]');
        const selectedCategoriesInput = document.getElementById('selected_categories');

        // Inisialisasi kategori yang sudah dipilih
        let selectedItems = new Set([
            @foreach($article->categories as $category)
                "{{ $category->id }}",
            @endforeach
        ]);

        // Toggle dropdown
        dropdownButton?.addEventListener('click', () => {
            dropdown.classList.toggle('hidden');
        });

        // Fungsi pencarian kategori
        searchInput?.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            categoryItems.forEach(item => {
                const name = item.getAttribute('data-name').toLowerCase();
                item.style.display = name.includes(searchTerm) ? 'block' : 'none';
            });
        });

        // Fungsi untuk menambah kategori
        function addCategory(id, name) {
            if (!selectedItems.has(id)) {
                selectedItems.add(id);
                const categoryElement = createCategoryElement(id, name);
                selectedContainer.appendChild(categoryElement);
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'category_ids[]';
                input.value = id;
                selectedCategoriesInput.appendChild(input);
            }
        }

        // Fungsi untuk membuat elemen kategori
        function createCategoryElement(id, name) {
            const div = document.createElement('div');
            div.className = 'flex justify-center items-center m-1 font-medium py-1 px-2 bg-white rounded-full text-[#a10d05] bg-[#a10d05] bg-opacity-10 border border-[#a10d05]';
            div.innerHTML = `
                <div class="text-xs font-normal leading-none max-w-full flex-initial">${name}</div>
                <div class="flex flex-auto flex-row-reverse">
                    <div class="cursor-pointer remove-category ml-2" data-value="${id}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x cursor-pointer hover:text-[#a10d05] rounded-full w-4 h-4">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </div>
                </div>
            `;
            return div;
        }

        // Event listener untuk menambah kategori
        categoryItems.forEach(item => {
            item.addEventListener('click', () => {
                const id = item.getAttribute('data-value');
                const name = item.getAttribute('data-name');
                addCategory(id, name);
            });
        });

        // Event listener untuk menghapus kategori
        selectedContainer.addEventListener('click', (e) => {
            if (e.target.closest('.remove-category')) {
                const id = e.target.closest('.remove-category').getAttribute('data-value');
                selectedItems.delete(id);
                e.target.closest('.flex.justify-center').remove();
                selectedCategoriesInput.querySelector(`input[value="${id}"]`)?.remove();
            }
        });
    });

    // Script untuk TinyMCE
    tinymce.init({
        selector: '#editor',
        // ... konfigurasi TinyMCE sama seperti di createarticle.blade.php ...
    });
</script>
@endpush

@endsection


