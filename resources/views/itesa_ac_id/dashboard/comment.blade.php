@extends('indexdash')

@section('title', 'Komentar')

@section('content')
<!-- Breadcrumb -->
<nav class="bg-white p-4 mb-6 rounded-lg shadow-sm">
    <ol class="flex items-center space-x-2 text-gray-600">
        <li><a href="{{ route('admin.dashboard') }}" class="hover:text-[#D91656]">Beranda</a></li>
        <li><span class="mx-2">/</span></li>
        <li class="text-[#a10d05]">Komentar</li>
    </ol>
</nav>

<!-- Content Komentar -->
<div class="bg-white rounded-lg shadow-sm p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Kelola Komentar</h2>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pengguna</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Artikel</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Komentar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($comments as $comment)
                <tr>
                    <td class="px-6 py-4">{{ $comment->user->username }}</td>
                    <td class="px-6 py-4">{{ Str::limit($comment->article->title, 50) }}</td>
                    <td class="px-6 py-4">{{ Str::limit($comment->content, 100) }}</td>
                    <td class="px-6 py-4">{{ $comment->created_at->format('d M Y H:i') }}</td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.comment.destroy', $comment->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus komentar ini?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada komentar
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($comments instanceof \Illuminate\Pagination\LengthAwarePaginator && $comments->hasPages())
    <div class="px-6 py-4 border-t">
        {{ $comments->links() }}
    </div>
    @endif
</div>
@endsection
