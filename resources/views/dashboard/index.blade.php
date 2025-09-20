{{-- File: resources/views/dashboard/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h2>
    
    <!-- Filter -->
    <div class="btn-group" role="group">
        <a href="{{ route('dashboard', ['filter' => 'all']) }}" 
           class="btn {{ $filter === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
            Semua
        </a>
        <a href="{{ route('dashboard', ['filter' => 'available']) }}" 
           class="btn {{ $filter === 'available' ? 'btn-success' : 'btn-outline-success' }}">
            Tersedia
        </a>
        <a href="{{ route('dashboard', ['filter' => 'occupied']) }}" 
           class="btn {{ $filter === 'occupied' ? 'btn-danger' : 'btn-outline-danger' }}">
            Terisi
        </a>
        <a href="{{ route('dashboard', ['filter' => 'cleaning']) }}" 
           class="btn {{ $filter === 'cleaning' ? 'btn-warning' : 'btn-outline-warning' }}">
            Cleaning
        </a>
    </div>
</div>

<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $stats['total_rooms'] }}</h4>
                        <p class="mb-0">Total Kamar</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-bed fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $stats['available_rooms'] }}</h4>
                        <p class="mb-0">Kamar Tersedia</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $stats['occupied_rooms'] }}</h4>
                        <p class="mb-0">Kamar Terisi</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $stats['cleaning_rooms'] }}</h4>
                        <p class="mb-0">Perlu Cleaning</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-broom fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Room Cards -->
<div class="row">
    @forelse($rooms as $room)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card room-card room-status-{{ $room->status }}" 
                 @if($user->canManageRooms() && $room->isAvailable()) 
                     onclick="window.location='{{ route('bookings.create', $room) }}'"
                 @elseif($room->isOccupied() && $user->canManageRooms()) 
                     onclick="showRoomDetails({{ $room->id }})"
                 @endif>
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $room->room_number }}</h5>
                    @if($room->isAvailable())
                        <span class="badge bg-success">Tersedia</span>
                    @elseif($room->isOccupied())
                        <span class="badge bg-danger">Terisi</span>
                    @elseif($room->needsCleaning())
                        <span class="badge bg-warning">Cleaning</span>
                    @endif
                </div>
                
                @if($room->image)
                    <img src="{{ asset('storage/' . $room->image) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $room->room_name }}">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="fas fa-image fa-3x text-muted"></i>
                    </div>
                @endif
                
                <div class="card-body">
                    <h6 class="card-title">{{ $room->room_name }}</h6>
                    <p class="text-muted mb-2">Rp {{ number_format($room->price, 0, ',', '.') }} / jam</p>
                    
                    @if($room->isOccupied() && $room->activeBooking)
                        <div class="mb-2">
                            <strong>Tamu:</strong> {{ $room->activeBooking->guest_name }}<br>
                            <strong>Check-out:</strong> {{ $room->activeBooking->check_out->format('d/m/Y H:i') }}<br>
                            <strong>Waktu Tersisa:</strong>
                            <div class="timer text-primary" data-timer="{{ $room->activeBooking->check_out->toISOString() }}">
                                {{ $room->getRemainingTime() }}
                            </div>
                        </div>
                    @endif
                    
                    @if($room->description)
                        <p class="card-text text-muted small">{{ Str::limit($room->description, 60) }}</p>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>Tidak ada kamar yang ditemukan.
            </div>
        </div>
    @endforelse
</div>

<!-- Room Details Modal -->
<div class="modal fade" id="roomDetailsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Kamar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="roomDetailsContent">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showRoomDetails(roomId) {
    // Find room data
    const rooms = @json($rooms);
    const room = rooms.find(r => r.id === roomId);
    
    if (!room || !room.active_booking) {
        return;
    }
    
    const booking = room.active_booking;
    const checkOut = new Date(booking.check_out);
    const now = new Date();
    const isExpired = now >= checkOut;
    
    let content = `
        <div class="mb-3">
            <h6><i class="fas fa-bed me-2"></i>${room.room_number} - ${room.room_name}</h6>
        </div>
        <div class="row mb-3">
            <div class="col-6"><strong>Tamu:</strong></div>
            <div class="col-6">${booking.guest_name}</div>
        </div>
        <div class="row mb-3">
            <div class="col-6"><strong>Check-in:</strong></div>
            <div class="col-6">${new Date(booking.check_in).toLocaleString('id-ID')}</div>
        </div>
        <div class="row mb-3">
            <div class="col-6"><strong>Check-out:</strong></div>
            <div class="col-6">${checkOut.toLocaleString('id-ID')}</div>
        </div>
        <div class="row mb-3">
            <div class="col-6"><strong>Total Harga:</strong></div>
            <div class="col-6">Rp ${new Intl.NumberFormat('id-ID').format(booking.total_price)}</div>
        </div>
        <div class="row mb-3">
            <div class="col-6"><strong>Status:</strong></div>
            <div class="col-6">
                ${isExpired ? '<span class="badge bg-warning">Expired</span>' : '<span class="badge bg-success">Aktif</span>'}
            </div>
        </div>
    `;
    
    if (!isExpired) {
        content += `
            <div class="row mb-3">
                <div class="col-6"><strong>Waktu Tersisa:</strong></div>
                <div class="col-6">
                    <span class="timer text-primary" data-timer="${booking.check_out}"></span>
                </div>
            </div>
        `;
    }
    
    content += `
        <div class="d-grid">
            <form action="{{ url('/bookings') }}/${booking.id}/complete" method="POST" 
                  onsubmit="return confirm('Yakin ingin menyelesaikan pemesanan ini?')">
                @csrf
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-check me-2"></i>Selesaikan Pemesanan
                </button>
            </form>
        </div>
    `;
    
    document.getElementById('roomDetailsContent').innerHTML = content;
    
    const modal = new bootstrap.Modal(document.getElementById('roomDetailsModal'));
    modal.show();
}
</script>
@endpush