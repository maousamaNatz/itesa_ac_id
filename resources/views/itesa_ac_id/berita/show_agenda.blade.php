@extends('indexberita')

@section('content')
    <!-- Header Agenda -->
    <div class="agenda-header">
        <h1>Agenda Kampus</h1>
        <p>Jadwal kegiatan dan acara penting di ITESA Muhammadiyah</p>
    </div>

    <!-- Debug info -->
    @if(isset($agendas))
        <div style="display:none">
            {{ print_r($agendas->toArray()) }}
        </div>
    @endif

    <!-- Daftar Agenda -->
    <div class="agenda-grid">
        @forelse ($agendas as $item)
            <!-- Item Agenda -->
            <div class="agenda-card">
                <div class="agenda-date">
                    <span class="day">{{ $item->start_date->format('d') }}</span>
                    <span class="month">{{ $item->start_date->format('M') }}</span>
                    <span class="year">{{ $item->start_date->format('Y') }}</span>
                </div>
                <div class="agenda-content">
                    <span class="agenda-time"><i class="far fa-clock"></i> {{ $item->start_date->format('H:i') }}</span>
                    <h3>{{ $item->title }}</h3>
                    <p>{!! Str::limit(strip_tags($item->description), 100, '...') !!}</p>
                    <div class="agenda-details">
                        <span><i class="fas fa-map-marker-alt"></i> {{ $item->location }}</span>
                        <span><i class="fas fa-user-tie"></i> {{ $item->created_by }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="no-agenda">
                <p>Tidak ada agenda yang tersedia saat ini.</p>
            </div>
        @endforelse
    </div>
@endsection
