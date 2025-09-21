<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        // Gunakan middleware yang sama seperti canManageRooms()
        $this->middleware(function ($request, $next) {
            $user = $request->user();

            // Gunakan method canManageRooms() yang sudah ada
            if (!$user->canManageRooms()) {
                abort(403, 'Unauthorized access - Anda tidak memiliki akses untuk melihat laporan');
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = Booking::with(['room', 'user'])
            ->where('status', '!=', 'cancelled')
            ->orderBy('created_at', 'desc');

        // Apply date filters
        switch ($filter) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
            case 'custom':
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [
                        Carbon::parse($startDate)->startOfDay(),
                        Carbon::parse($endDate)->endOfDay()
                    ]);
                }
                break;
        }

        $bookings = $query->paginate(20);
        $totalRevenue = $query->sum('total_price');
        $totalBookings = $query->count();

        // Get summary data
        $summary = [
            'total_bookings' => $totalBookings,
            'total_revenue' => $totalRevenue,
            'completed_bookings' => $query->where('status', 'completed')->count(),
            'active_bookings' => $query->where('status', 'active')->count(),
        ];

        return view('reports.index', compact(
            'bookings',
            'filter',
            'summary',
            'startDate',
            'endDate'
        ));
    }

    public function exportPdf(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = Booking::with(['room', 'user'])
            ->where('status', '!=', 'cancelled')
            ->orderBy('created_at', 'desc');

        // Apply same filters as index
        switch ($filter) {
            case 'today':
                $query->whereDate('created_at', today());
                $periodTitle = 'Hari Ini (' . today()->format('d/m/Y') . ')';
                break;
            case 'week':
                $query->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ]);
                $periodTitle = 'Minggu Ini (' . now()->startOfWeek()->format('d/m/Y') . ' - ' . now()->endOfWeek()->format('d/m/Y') . ')';
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
                $periodTitle = now()->format('F Y');
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                $periodTitle = 'Tahun ' . now()->year;
                break;
            case 'custom':
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [
                        Carbon::parse($startDate)->startOfDay(),
                        Carbon::parse($endDate)->endOfDay()
                    ]);
                    $periodTitle = Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y');
                } else {
                    $periodTitle = 'Semua Data';
                }
                break;
            default:
                $periodTitle = 'Semua Data';
                break;
        }

        $bookings = $query->get();
        $totalRevenue = $bookings->sum('total_price');
        $totalBookings = $bookings->count();

        $data = [
            'bookings' => $bookings,
            'total_revenue' => $totalRevenue,
            'total_bookings' => $totalBookings,
            'period_title' => $periodTitle,
            'generated_at' => now()->format('d/m/Y H:i:s'),
            'hotel_name' => config('app.name', 'Hotel Booking System')
        ];

        $pdf = PDF::loadView('reports.pdf', $data);
        $pdf->setPaper('A4', 'landscape');

        $filename = 'laporan-pemesanan-' . now()->format('Y-m-d-His') . '.pdf';

        return $pdf->download($filename);
    }
}
