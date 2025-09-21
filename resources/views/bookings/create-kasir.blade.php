{{-- File: resources/views/bookings/create-kasir.blade.php --}}
@extends('layouts.kasir')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-plus me-2"></i>Pesan Kamar: {{ $room->room_number }}
                    </h5>
                </div>
                
                <form action="{{ route('kasir.bookings.store') }}" method="POST" id="bookingForm">
                    @csrf
                    <input type="hidden" name="room_id" value="{{ $room->id }}">
                    
                    <div class="card-body">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-7">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="guest_name" class="form-label">Nama Tamu <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('guest_name') is-invalid @enderror" 
                                               id="guest_name" name="guest_name" value="{{ old('guest_name') }}" required>
                                        @error('guest_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="guest_phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('guest_phone') is-invalid @enderror" 
                                               id="guest_phone" name="guest_phone" value="{{ old('guest_phone') }}" 
                                               placeholder="08xxxxxxxxxx" required>
                                        @error('guest_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="check_in" class="form-label">Tanggal Booking <span class="text-danger">*</span></label>
                                        <input type="datetime-local" class="form-control @error('check_in') is-invalid @enderror" 
                                               id="check_in" name="check_in" value="{{ old('check_in', now()->format('Y-m-d\TH:i')) }}" required>
                                        @error('check_in')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="duration_hours" class="form-label">Durasi (Jam) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('duration_hours') is-invalid @enderror" 
                                               id="duration_hours" name="duration_hours" value="{{ old('duration_hours', 1) }}" 
                                               min="1" max="720" required>
                                        <small class="form-text text-muted">Maksimal 720 jam (30 hari)</small>
                                        @error('duration_hours')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="total_price" class="form-label">Total Harga <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control @error('total_price') is-invalid @enderror" 
                                                   id="total_price" name="total_price" value="{{ old('total_price') }}" 
                                                   min="0" readonly>
                                        </div>
                                        <small class="form-text text-muted">Harga dasar: Rp {{ number_format($room->price, 0, ',', '.') }} per jam</small>
                                        @error('total_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="payment_method" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                                        <select class="form-select @error('payment_method') is-invalid @enderror" 
                                                id="payment_method" name="payment_method" required>
                                            <option value="">Pilih metode pembayaran</option>
                                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                            <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                        </select>
                                        @error('payment_method')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Catatan</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" name="notes" rows="3" 
                                              placeholder="Tambahkan catatan khusus untuk pemesanan ini...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Right Column - Room Info -->
                            <div class="col-md-5">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Detail Kamar</h6>
                                    </div>
                                    
                                    @if($room->image)
                                        <img src="{{ asset('storage/' . $room->image) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $room->room_name }}">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                    
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $room->room_name }}</h5>
                                        <p class="text-muted mb-2">Nomor: {{ $room->room_number }}</p>
                                        
                                        <div class="mb-3">
                                            <h6 class="text-success">Rp {{ number_format($room->price, 0, ',', '.') }} / jam</h6>
                                        </div>
                                        
                                        @if($room->description)
                                            <p class="card-text text-muted small">{{ $room->description }}</p>
                                        @endif
                                        
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <strong>Pemesanan akan dimulai saat ini dan berakhir sesuai durasi yang dipilih.</strong>
                                        </div>
                                        
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <small>Pastikan nomor telepon aktif untuk konfirmasi pemesanan.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('kasir.landing') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Buat Pemesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const durationInput = document.getElementById('duration_hours');
    const totalPriceInput = document.getElementById('total_price');
    const basePrice = {{ $room->price }};
    
    function calculateTotal() {
        const duration = parseInt(durationInput.value) || 0;
        const total = duration * basePrice;
        totalPriceInput.value = total;
    }
    
    durationInput.addEventListener('input', calculateTotal);
    calculateTotal(); // Calculate on page load
});
</script>
@endsection