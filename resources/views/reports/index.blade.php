{{-- File: resources/views/reports/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-file-alt me-2"></i>Cetak Laporan Pemesanan</h2>
    
    <!-- Export PDF Button -->
    <button type="button" class="btn btn-primary" onclick="exportToPdf()">
        <i class="fas fa-file-pdf me-2"></i>Export PDF
    </button>
</div>

<!-- Filter Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $summary['total_bookings'] }}</h4>
                        <p class="mb-0">Total Pemesanan</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar-check fa-2x"></i>
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
                        <h5>Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</h5>
                        <p class="mb-0">Total Pendapatan</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-money-bill-wave fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $summary['completed_bookings'] }}</h4>
                        <p class="mb-0">Selesai</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
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
                        <h4>{{ $summary['active_bookings'] }}</h4>
                        <p class="mb-0">Aktif</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Form -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.index') }}" id="filterForm">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Filter Periode:</label>
                    <select name="filter" class="form-select" id="filterSelect" onchange="toggleCustomDate()">
                        <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>Semua Data</option>
                        <option value="today" {{ $filter === 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="week" {{ $filter === 'week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="month" {{ $filter === 'month' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="year" {{ $filter === 'year' ? 'selected' : '' }}>Tahun Ini</option>
                        <option value="custom" {{ $filter === 'custom' ? 'selected' : '' }}>Periode Tertentu</option>
                    </select>
                </div>
                
                <div class="col-md-3" id="startDateGroup" style="{{ $filter !== 'custom' ? 'display: none;' : '' }}">
                    <label class="form-label">Tanggal Mulai:</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                
                <div class="col-md-3" id="endDateGroup" style="{{ $filter !== 'custom' ? 'display: none;' : '' }}">
                    <label class="form-label">Tanggal Akhir:</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                        <i class="fas fa-refresh me-2"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Data Table -->
<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-4">Data Pemesanan</h5>
        
        @if($bookings->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Tanggal Pesan</th>
                            <th>Nama Pemesan</th>
                            <th>No. Telepon</th>
                            <th>Kamar</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Durasi</th>
                            <th>Harga</th>
                            <th>Metode Bayar</th>
                            <th>Status</th>
                            <th>Kasir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $index => $booking)
                        <tr>
                            <td>{{ $bookings->firstItem() + $index }}</td>
                            <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $booking->guest_name }}</td>
                            <td>
                                <span class="text-muted small">
                                    <i class="fas fa-phone me-1"></i>{{ $booking->phone_number }}
                                </span>
                            </td>
                            <td>{{ $booking->room->room_number }} - {{ $booking->room->room_name }}</td>
                            <td>{{ $booking->check_in->format('d/m/Y H:i') }}</td>
                            <td>{{ $booking->check_out->format('d/m/Y H:i') }}</td>
                            <td>
                                @php
                                    $duration = $booking->check_in->diffInHours($booking->check_out);
                                @endphp
                                {{ $duration }} jam
                            </td>
                            <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                            <td>
                                @if($booking->payment_method === 'cash')
                                    <span class="badge bg-success">
                                        <i class="fas fa-money-bill-wave me-1"></i>Cash
                                    </span>
                                @elseif($booking->payment_method === 'transfer')
                                    <span class="badge bg-primary">
                                        <i class="fas fa-credit-card me-1"></i>Transfer
                                    </span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($booking->payment_method) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->status === 'active')
                                    <span class="badge bg-success">Aktif</span>
                                @elseif($booking->status === 'completed')
                                    <span class="badge bg-primary">Selesai</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
                                @endif
                            </td>
                            <td>{{ $booking->user->name ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-secondary">
                        <tr>
                            <th colspan="8" class="text-end">Total Pendapatan:</th>
                            <th>Rp {{ number_format($bookings->sum('total_price'), 0, ',', '.') }}</th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    Menampilkan {{ $bookings->firstItem() }} - {{ $bookings->lastItem() }} 
                    dari {{ $bookings->total() }} data
                </div>
                {{ $bookings->appends(request()->query())->links() }}
            </div>
        @else
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>
                Tidak ada data pemesanan untuk periode yang dipilih.
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleCustomDate() {
    const filter = document.getElementById('filterSelect').value;
    const startDateGroup = document.getElementById('startDateGroup');
    const endDateGroup = document.getElementById('endDateGroup');
    
    if (filter === 'custom') {
        startDateGroup.style.display = 'block';
        endDateGroup.style.display = 'block';
    } else {
        startDateGroup.style.display = 'none';
        endDateGroup.style.display = 'none';
    }
}

function exportToPdf() {
    const form = document.getElementById('filterForm');
    const url = new URL('{{ route("reports.export-pdf") }}', window.location.origin);
    
    // Add current filter parameters to PDF export URL
    const formData = new FormData(form);
    for (let [key, value] of formData.entries()) {
        if (value) {
            url.searchParams.append(key, value);
        }
    }
    
    window.open(url.toString(), '_blank');
}

// Auto submit form when filter changes (except custom)
document.getElementById('filterSelect').addEventListener('change', function() {
    if (this.value !== 'custom') {
        document.getElementById('filterForm').submit();
    }
});
</script>
@endpush