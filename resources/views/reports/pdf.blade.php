<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pemesanan Kamar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        
        .hotel-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .hotel-info {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 10px;
        }
        
        .report-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .report-period {
            font-size: 14px;
            color: #34495e;
        }
        
        .summary-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        
        .summary-table td {
            padding: 8px;
            border: 1px solid #bdc3c7;
        }
        
        .summary-table .label {
            background-color: #ecf0f1;
            font-weight: bold;
            width: 150px;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .data-table th,
        .data-table td {
            padding: 6px;
            border: 1px solid #bdc3c7;
            text-align: left;
            font-size: 10px;
        }
        
        .data-table th {
            background-color: #34495e;
            color: white;
            font-weight: bold;
        }
        
        .data-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .total-row {
            background-color: #e8f4fd !important;
            font-weight: bold;
        }
        
        .total-row td {
            border-top: 2px solid #34495e;
        }
        
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #7f8c8d;
        }
        
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            color: white;
            font-size: 9px;
        }
        
        .status-active { background-color: #27ae60; }
        .status-completed { background-color: #3498db; }
        .status-cancelled { background-color: #e74c3c; }
        
        .page-break {
            page-break-before: always;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="hotel-name">{{ $hotel_name }}</div>
        <div class="hotel-info">
            Sistem Manajemen Pemesanan Kamar Hotel<br>
            Jl. Hotel Mewah No. 123, Kota Impian
        </div>
        <div class="report-title">LAPORAN PEMESANAN KAMAR</div>
        <div class="report-period">Periode: {{ $period_title }}</div>
    </div>

    <!-- Summary -->
    <table class="summary-table">
        <tr>
            <td class="label">Total Pemesanan</td>
            <td>{{ $total_bookings }} pemesanan</td>
            <td class="label">Total Pendapatan</td>
            <td class="text-right">Rp {{ number_format($total_revenue, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Cetak</td>
            <td>{{ $generated_at }}</td>
            <td class="label">Status Laporan</td>
            <td>Final</td>
        </tr>
    </table>

    <!-- Data Table -->
    @if($bookings->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="12%">Tgl Pesan</th>
                    <th width="15%">Nama Pemesan</th>
                    <th width="15%">Kamar</th>
                    <th width="10%">Check-in</th>
                    <th width="10%">Check-out</th>
                    <th width="8%">Durasi</th>
                    <th width="12%">Harga</th>
                    <th width="8%">Status</th>
                    <th width="10%">Kasir</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $index => $booking)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $booking->guest_name }}</td>
                    <td>{{ $booking->room->room_number }} - {{ Str::limit($booking->room->room_name, 15) }}</td>
                    <td>{{ $booking->check_in->format('d/m H:i') }}</td>
                    <td>{{ $booking->check_out->format('d/m H:i') }}</td>
                    <td class="text-center">
                        @php
                            $duration = $booking->check_in->diffInHours($booking->check_out);
                        @endphp
                        {{ $duration }}j
                    </td>
                    <td class="text-right">{{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <span class="status-badge status-{{ $booking->status }}">
                            @if($booking->status === 'active')
                                Aktif
                            @elseif($booking->status === 'completed')
                                Selesai
                            @else
                                {{ ucfirst($booking->status) }}
                            @endif
                        </span>
                    </td>
                    <td>{{ $booking->user->name ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="7" class="text-right"><strong>TOTAL PENDAPATAN:</strong></td>
                    <td class="text-right"><strong>Rp {{ number_format($total_revenue, 0, ',', '.') }}</strong></td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
    @else
        <div style="text-align: center; padding: 40px; color: #7f8c8d;">
            <h3>Tidak ada data pemesanan untuk periode yang dipilih</h3>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem pada {{ $generated_at }}</p>
        <p>{{ $hotel_name }} - Hotel Management System</p>
    </div>
</body>
</html>