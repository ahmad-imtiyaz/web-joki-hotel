{{-- File: resources/views/bookings/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-calendar-plus me-2"></i>Pesan Kamar: {{ $room->room_number }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('bookings.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="room_id" value="{{ $room->id }}">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="guest_name" class="form-label">Nama Tamu <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('guest_name') is-invalid @enderror" 
                                       id="guest_name" name="guest_name" value="{{ old('guest_name') }}" required>
                                @error('guest_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" 
                                       id="phone_number" name="phone_number" value="{{ old('phone_number') }}" 
                                       placeholder="08xxxxxxxxxx" required>
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="booking_date" class="form-label">Tanggal Booking <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('booking_date') is-invalid @enderror" 
                                       id="booking_date" name="booking_date" 
                                       value="{{ old('booking_date', date('Y-m-d')) }}" required>
                                @error('booking_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="duration_hours" class="form-label">Durasi (Jam) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('duration_hours') is-invalid @enderror" 
                                       id="duration_hours" name="duration_hours" 
                                       value="{{ old('duration_hours', 1) }}" min="1" max="720" required>
                                @error('duration_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Maksimal 720 jam (30 hari)</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="total_price" class="form-label">Total Harga <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('total_price') is-invalid @enderror" 
                                           id="total_price" name="total_price" 
                                           value="{{ old('total_price', $room->price) }}" min="0" required>
                                </div>
                                @error('total_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Harga dasar: Rp {{ number_format($room->price, 0, ',', '.') }} per jam
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                                <select class="form-select @error('payment_method') is-invalid @enderror" 
                                        id="payment_method" name="payment_method" required>
                                    <option value="">Pilih metode pembayaran</option>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>
                                        <i class="fas fa-money-bill-wave"></i> Cash
                                    </option>
                                    <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>
                                        <i class="fas fa-credit-card"></i> Transfer
                                    </option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
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
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6><i class="fas fa-info-circle me-2"></i>Detail Kamar</h6>
            </div>
            <div class="card-body">
                @if($room->image)
                    <img src="{{ asset('storage/' . $room->image) }}" class="img-fluid mb-3 rounded" alt="{{ $room->room_name }}">
                @endif
                
                <h6>{{ $room->room_name }}</h6>
                <p class="text-muted">Nomor: {{ $room->room_number }}</p>
                <p class="text-success fw-bold">Rp {{ number_format($room->price, 0, ',', '.') }} / jam</p>
                
                @if($room->description)
                    <p class="text-muted small">{{ $room->description }}</p>
                @endif
                
                <div class="alert alert-info">
                    <small>
                        <i class="fas fa-info-circle me-1"></i>
                        Pemesanan akan dimulai saat ini dan berakhir sesuai durasi yang dipilih.
                    </small>
                </div>
                
                <div class="alert alert-warning">
                    <small>
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Pastikan nomor telepon aktif untuk konfirmasi pemesanan.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const durationInput = document.getElementById('duration_hours');
    const priceInput = document.getElementById('total_price');
    const paymentMethod = document.getElementById('payment_method');
    const basePrice = {{ $room->price }};
    
    function calculateTotal() {
        const duration = parseInt(durationInput.value) || 1;
        const total = basePrice * duration;
        priceInput.value = total;
    }
    
    // Auto calculate total when duration changes
    durationInput.addEventListener('input', calculateTotal);
    
    // Add icon to payment method options (optional visual enhancement)
    paymentMethod.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            console.log('Selected payment method:', selectedOption.value);
        }
    });
    
    // Phone number formatting (optional)
    const phoneInput = document.getElementById('phone_number');
    phoneInput.addEventListener('input', function() {
        // Remove non-numeric characters except +, -, and spaces
        this.value = this.value.replace(/[^0-9+\-\s]/g, '');
    });
    
    // Initial calculation
    calculateTotal();
});
</script>
@endpush