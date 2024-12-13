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
        <li class="text-[#a10d05]">Agenda</li>
    </ol>
</nav>

<!-- Content Kategori -->
<div class="bg-white rounded-lg shadow-sm p-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Kelola Agenda</h2>
        <a href="{{ route('admin.agenda.create') }}" class="bg-[#a10d05] text-white px-4 py-2 rounded-lg hover:bg-[#8f0b04] flex items-center gap-2">
            <i class="fas fa-plus"></i>
            Tambah Agenda
        </a>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Agenda</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Mulai</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Selesai</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($agendas as $agenda)
                <tr>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $agenda->title }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $agenda->start_date->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $agenda->end_date->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $agenda->location }}</td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.agenda.edit', $agenda->id) }}" class="text-[#a10d05] hover:text-[#D91656]" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.agenda.destroy', $agenda->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus agenda ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada agenda yang ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($agendas->hasPages())
    <div class="flex items-center justify-between mt-6">
        <div class="text-sm text-gray-500">
            Menampilkan {{ $agendas->firstItem() }} - {{ $agendas->lastItem() }} dari {{ $agendas->total() }} agenda
        </div>
        <div class="flex items-center space-x-2">
            {{ $agendas->links() }}
        </div>
    </div>
    @endif
</div>

<script src="{{ asset('lib/js/filter.js') }}"></script>
<script src="{{ asset('lib/js/filterarticle.js') }}"></script>
@endsection

