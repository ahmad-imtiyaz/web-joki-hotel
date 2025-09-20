{{-- File: resources/views/cleaning/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-broom me-2"></i>Cleaning Management</h2>
    <div class="badge bg-info fs-6">
        Total Notifikasi: {{ $notifications->total() }}
    </div>
</div>

@if($notifications->count() > 0)
    <div class="row">
        @foreach($notifications as $notification)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card {{ $notification->isPending() ? 'border-warning' : ($notification->isInProgress() ? 'border-info' : 'border-success') }}">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-bed me-2"></i>{{ $notification->room->room_number }}
                        </h6>
                        @if($notification->isPending())
                            <span class="badge bg-warning">Pending</span>
                        @elseif($notification->isInProgress())
                            <span class="badge bg-info">In Progress</span>
                        @else
                            <span class="badge bg-success">Completed</span>
                        @endif
                    </div>
                    
                    <div class="card-body">
                        <h6 class="card-title">{{ $notification->room->room_name }}</h6>
                        
                        <div class="mb-2">
                            <small class="text-muted">
                                <strong>Tamu Sebelumnya:</strong> {{ $notification->booking->guest_name }}<br>
                                <strong>Check-out:</strong> {{ $notification->booking->check_out->format('d/m/Y H:i') }}<br>
                                <strong>Durasi:</strong> {{ $notification->booking->getDurationInWords() }}<br>
                                <strong>Dibuat:</strong> {{ $notification->created_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                        
                        @if($notification->assignedUser)
                            <div class="mb-2">
                                <small class="text-info">
                                    <i class="fas fa-user me-1"></i>
                                    Ditangani oleh: {{ $notification->assignedUser->name }}
                                </small>
                            </div>
                        @endif
                        
                        @if($notification->completed_at)
                            <div class="mb-2">
                                <small class="text-success">
                                    <i class="fas fa-check me-1"></i>
                                    Selesai: {{ $notification->completed_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                        @endif
                        
                        <div class="btn-group w-100">
                            @if($notification->isPending())
                                <form action="{{ route('cleaning.accept', $notification) }}" method="POST" class="flex-fill">
                                    @csrf
                                    <button type="submit" class="btn btn-info w-100">
                                        <i class="fas fa-hand-paper me-1"></i>Terima Tugas
                                    </button>
                                </form>
                            @elseif($notification->isInProgress() && (auth()->user()->isSuperAdmin() || $notification->assigned_to === auth()->id()))
                                <form action="{{ route('cleaning.complete', $notification) }}" method="POST" class="flex-fill">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100"
                                            onclick="return confirm('Yakin sudah selesai membersihkan kamar {{ $notification->room->room_number }}?')">
                                        <i class="fas fa-check me-1"></i>Selesai
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-secondary w-100" disabled>
                                    <i class="fas fa-check me-1"></i>Sudah Selesai
                                </button>
                            @endif
                        </div>
                    </div>
                    
                    <div class="card-footer text-center">
                        <small class="text-muted">
                            Booking oleh: {{ $notification->booking->user->name }}
                        </small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="d-flex justify-content-center">
            {{ $notifications->links() }}
        </div>
    @endif
    
@else
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-broom fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak Ada Tugas Cleaning</h5>
                    <p class="text-muted">Semua kamar sudah bersih dan siap digunakan.</p>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
// Auto refresh page every 30 seconds to check for new notifications
setInterval(function() {
    // Only refresh if there are pending notifications
    const pendingNotifications = document.querySelectorAll('.border-warning');
    if (pendingNotifications.length > 0) {
        location.reload();
    }
}, 30000);
</script>
@endpush