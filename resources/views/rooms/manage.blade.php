{{-- File: resources/views/rooms/manage.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-bed me-2"></i>Kelola Kamar</h2>
    <a href="{{ route('rooms.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah Kamar
    </a>
</div>

<!-- Filter -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="btn-group" role="group">
            <a href="{{ route('rooms.manage', ['filter' => 'all']) }}" 
               class="btn {{ $filter === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                Semua
            </a>
            <a href="{{ route('rooms.manage', ['filter' => 'available']) }}" 
               class="btn {{ $filter === 'available' ? 'btn-success' : 'btn-outline-success' }}">
                Tersedia
            </a>
            <a href="{{ route('rooms.manage', ['filter' => 'occupied']) }}" 
               class="btn {{ $filter === 'occupied' ? 'btn-danger' : 'btn-outline-danger' }}">
                Terisi
            </a>
            <a href="{{ route('rooms.manage', ['filter' => 'cleaning']) }}" 
               class="btn {{ $filter === 'cleaning' ? 'btn-warning' : 'btn-outline-warning' }}">
                Cleaning
            </a>
        </div>
    </div>
</div>

<!-- Rooms Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Gambar</th>
                        <th>Nomor</th>
                        <th>Nama Kamar</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Tamu Saat Ini</th>
                        <th>Waktu Tersisa</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rooms as $room)
                        <tr>
                            <td>
                                @if($room->image)
                                    <img src="{{ asset('storage/' . $room->image) }}" 
                                         class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;" 
                                         alt="{{ $room->room_name }}">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td><strong>{{ $room->room_number }}</strong></td>
                            <td>{{ $room->room_name }}</td>
                            <td>Rp {{ number_format($room->price, 0, ',', '.') }}</td>
                            <td>
                                @if($room->isAvailable())
                                    <span class="badge bg-success">Tersedia</span>
                                @elseif($room->isOccupied())
                                    <span class="badge bg-danger">Terisi</span>
                                @elseif($room->needsCleaning())
                                    <span class="badge bg-warning">Cleaning</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($room->status) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($room->activeBooking)
                                    {{ $room->activeBooking->guest_name }}
                                    <br><small class="text-muted">oleh {{ $room->activeBooking->user->name }}</small>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($room->activeBooking)
                                    <span class="timer" data-timer="{{ $room->activeBooking->check_out->toISOString() }}">
                                        {{ $room->getRemainingTime() }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('rooms.edit', $room) }}" 
                                       class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($room->isAvailable())
                                        <a href="{{ route('bookings.create', $room) }}" 
                                           class="btn btn-outline-success" title="Pesan">
                                            <i class="fas fa-calendar-plus"></i>
                                        </a>
                                    @endif
                                    @if(!$room->activeBooking)
                                        <form action="{{ route('rooms.destroy', $room) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus kamar ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-info-circle me-2"></i>Tidak ada kamar yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($rooms->hasPages())
            <div class="d-flex justify-content-center">
                {{ $rooms->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection