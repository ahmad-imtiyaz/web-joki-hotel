{{-- File: resources/views/users/profile.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-user me-2"></i>Profile Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" 
                               id="username" name="username" value="{{ old('username', $user->username) }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <input type="text" class="form-control" value="{{ ucfirst(str_replace('_', ' ', $user->role)) }}" readonly>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-lock me-2"></i>Ubah Password</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Password Lama <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                               id="current_password" name="current_password" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password Baru <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Password minimal 6 karakter.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" required>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key me-2"></i>Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- User Information Card -->
        <div class="card mt-4">
            <div class="card-header">
                <h6><i class="fas fa-info-circle me-2"></i>Informasi Akun</h6>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-6"><strong>Terdaftar:</strong></div>
                    <div class="col-6">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-6"><strong>Terakhir Update:</strong></div>
                    <div class="col-6">{{ $user->updated_at->format('d/m/Y H:i') }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-6"><strong>Status:</strong></div>
                    <div class="col-6">
                        <span class="badge bg-success">Aktif</span>
                    </div>
                </div>
                
                @if($user->isKasir() || $user->isSuperAdmin())
                    @php
                        $bookingCount = \App\Models\Booking::where('user_id', $user->id)->count();
                    @endphp
                    <div class="row mb-2">
                        <div class="col-6"><strong>Total Booking:</strong></div>
                        <div class="col-6">{{ $bookingCount }} pemesanan</div>
                    </div>
                @endif
                
                @if($user->isCleaning() || $user->isSuperAdmin())
                    @php
                        $cleaningCount = \App\Models\CleaningNotification::where('assigned_to', $user->id)->where('status', 'completed')->count();
                    @endphp
                    <div class="row mb-2">
                        <div class="col-6"><strong>Cleaning Selesai:</strong></div>
                        <div class="col-6">{{ $cleaningCount }} kamar</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection