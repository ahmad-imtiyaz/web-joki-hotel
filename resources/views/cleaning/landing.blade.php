{{-- File: resources/views/cleaning/landing.blade.php --}}
@extends('layouts.cleaning')

@section('content')
<!-- Welcome Section -->
<div class="welcome-section">
    <div class="container">
        <div class="text-center">
            <h1><i class="fas fa-broom me-3"></i>Dashboard Cleaning</h1>
            <p class="lead mb-0">Kelola tugas pembersihan kamar hotel</p>
            <div class="mt-3">
                <span class="badge bg-light text-dark fs-6">
                    Total Tugas: {{ $notifications->total() }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="container">
    @if($notifications->count() > 0)
        <div class="row">
            @foreach($notifications as $notification)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card cleaning-card {{ $notification->isPending() ? 'border-warning' : ($notification->isInProgress() ? 'border-info' : 'border-success') }}">
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
                            
                            <div class="mb-3">
                                <small class="text-muted">
                                    <strong>Tamu Sebelumnya:</strong> {{ $notification->booking->guest_name }}<br>
                                    <strong>Check-out:</strong> {{ $notification->booking->check_out->format('d/m/Y H:i') }}<br>
                                    <strong>Durasi:</strong> {{ $notification->booking->getDurationInWords() }}<br>
                                    <strong>Dibuat:</strong> {{ $notification->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                            
                            @if($notification->assignedUser)
                                <div class="mb-3">
                                    <small class="text-info">
                                        <i class="fas fa-user me-1"></i>
                                        Ditangani oleh: {{ $notification->assignedUser->name }}
                                    </small>
                                </div>
                            @endif
                            
                            @if($notification->completed_at)
                                <div class="mb-3">
                                    <small class="text-success">
                                        <i class="fas fa-check me-1"></i>
                                        Selesai: {{ $notification->completed_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            @endif
                            
                            <div class="d-grid">
                                @if($notification->isPending())
                                    <form action="{{ route('cleaning.accept', $notification) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-info">
                                            <i class="fas fa-hand-paper me-1"></i>Terima Tugas
                                        </button>
                                    </form>
                                @elseif($notification->isInProgress() && (auth()->user()->isSuperAdmin() || $notification->assigned_to === auth()->id()))
                                    <form action="{{ route('cleaning.complete', $notification) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success"
                                                onclick="return confirm('Yakin sudah selesai membersihkan kamar {{ $notification->room->room_number }}?')">
                                            <i class="fas fa-check me-1"></i>Selesai
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-secondary" disabled>
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
            <div class="col-md-8 col-lg-6">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-broom fa-4x text-success mb-4"></i>
                        <h4 class="text-success mb-3">Semua Kamar Sudah Bersih!</h4>
                        <p class="text-muted mb-4">Tidak ada tugas cleaning yang perlu dikerjakan saat ini.</p>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            Semua kamar sudah bersih dan siap digunakan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection