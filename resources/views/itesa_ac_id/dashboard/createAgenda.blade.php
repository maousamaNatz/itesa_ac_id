@extends('indexdash')

@section('title', 'Tambah Agenda')

@section('content')
<nav class="bg-white p-4 mb-6 rounded-lg shadow-sm">
    <ol class="flex items-center space-x-2 text-gray-600">
        <li><a href="{{ route('admin.dashboard') }}" class="hover:text-[#D91656]">Beranda</a></li>
        <li><span class="mx-2">/</span></li>
        <li><a href="{{ route('admin.agenda.index') }}" class="hover:text-[#D91656]">Agenda</a></li>
        <li><span class="mx-2">/</span></li>
        <li class="text-[#a10d05]">Tambah Agenda</li>
    </ol>
</nav>

<div class="bg-white rounded-lg shadow-sm p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Tambah Agenda Baru</h2>

    <form method="POST" action="{{ route('admin.agenda.store') }}">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Judul Agenda -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Judul Agenda</label>
                <input type="text"
                       name="title"
                       value="{{ old('title') }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-[#a10d05] focus:border-[#a10d05] @error('title') border-red-500 @enderror"
                       placeholder="Masukkan judul agenda..."
                       required>
                @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Lokasi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                <input type="text"
                       name="location"
                       value="{{ old('location') }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-[#a10d05] focus:border-[#a10d05] @error('location') border-red-500 @enderror"
                       placeholder="Masukkan lokasi agenda..."
                       required>
                @error('location')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Tanggal Mulai -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                <input type="datetime-local"
                       name="start_date"
                       value="{{ old('start_date') }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-[#a10d05] focus:border-[#a10d05] @error('start_date') border-red-500 @enderror"
                       required>
                @error('start_date')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal Selesai -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                <input type="datetime-local"
                       name="end_date"
                       value="{{ old('end_date') }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-[#a10d05] focus:border-[#a10d05] @error('end_date') border-red-500 @enderror"
                       required>
                @error('end_date')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Deskripsi -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Agenda</label>
            <textarea name="description"
                      rows="4"
                      class="w-full border rounded-lg px-4 py-2 focus:ring-[#a10d05] focus:border-[#a10d05] @error('description') border-red-500 @enderror"
                      placeholder="Masukkan deskripsi agenda..."
                      required>{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Status -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Status Agenda</label>
            <select name="status"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-[#a10d05] focus:border-[#a10d05] @error('status') border-red-500 @enderror">
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
            @error('status')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tombol Aksi -->
        <div class="flex justify-end space-x-4">
            <button type="reset"
                    class="px-6 py-2 border rounded-lg hover:bg-gray-50">
                Reset
            </button>
            <button type="submit"
                    class="px-6 py-2 bg-[#a10d05] text-white rounded-lg hover:bg-[#8f0b04]">
                Simpan Agenda
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Validasi tanggal
    document.addEventListener('DOMContentLoaded', function() {
        const startDate = document.querySelector('input[name="start_date"]');
        const endDate = document.querySelector('input[name="end_date"]');

        startDate.addEventListener('change', function() {
            endDate.min = this.value;
        });

        endDate.addEventListener('change', function() {
            if(this.value < startDate.value) {
                this.value = startDate.value;
            }
        });
    });
</script>
@endpush
@endsection

