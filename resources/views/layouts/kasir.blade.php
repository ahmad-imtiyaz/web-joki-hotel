{{-- File: resources/views/layouts/kasir.blade.php --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Hotel Booking') }} - Kasir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .room-card {
            transition: transform 0.2s;
            cursor: pointer;
        }
        .room-card:hover {
            transform: translateY(-5px);
        }
        .room-status-available {
            border-left: 5px solid #28a745;
        }
        .room-status-occupied {
            border-left: 5px solid #dc3545;
        }
        .room-status-cleaning {
            border-left: 5px solid #ffc107;
        }
        .content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .timer {
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }
        .navbar-brand {
            font-weight: bold;
            color: #ffffff !important;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .btn-dashboard {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            backdrop-filter: blur(10px);
            transition: all 0.3s;
        }
        .btn-dashboard:hover {
            background: rgba(255,255,255,0.3);
            border-color: rgba(255,255,255,0.5);
            color: white;
            transform: translateY(-1px);
        }
        .welcome-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div id="app">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <i class="fas fa-hotel me-2"></i>Hotel System - Kasir
                </a>
                
                <div class="navbar-nav ms-auto d-flex flex-row align-items-center">
                    <span class="text-white me-3">
                        <small>{{ auth()->user()->name }}</small>
                    </span>
                    <a href="{{ route('kasir.dashboard') }}" class="btn btn-dashboard me-2">
                        <i class="fas fa-file-alt me-1"></i>Dashboard
                    </a>
                    <a class="btn btn-outline-light" href="#" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </nav>

        <!-- Content -->
        <div class="content">
            @if(session('success'))
                <div class="container mt-3">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="container mt-3">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif
            
            @yield('content')
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto refresh timer every second
        setInterval(function() {
            const timers = document.querySelectorAll('[data-timer]');
            timers.forEach(function(timer) {
                const checkOut = new Date(timer.getAttribute('data-timer'));
                const now = new Date();
                
                if (now >= checkOut) {
                    timer.innerHTML = '<span class="text-danger">Expired</span>';
                    return;
                }
                
                const diff = checkOut - now;
                const hours = Math.floor(diff / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                
                timer.innerHTML = String(hours).padStart(2, '0') + ':' + 
                                 String(minutes).padStart(2, '0') + ':' + 
                                 String(seconds).padStart(2, '0');
            });
        }, 1000);
    </script>
    @stack('scripts')
</body>
</html>