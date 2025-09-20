<!doctype html>
{{-- File: resources/views/layouts/app.blade.php --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Hotel Booking') }}</title>
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
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }
        .sidebar .nav-link {
            color: #ffffff;
            border-radius: 5px;
            margin-bottom: 5px;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #495057;
            color: #ffffff;
        }
        .content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .timer {
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }
        .badge-notification {
            position: relative;
            top: -8px;
            left: -5px;
        }
    </style>
</head>
<body>
    <div id="app">
        @auth
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-3 col-lg-2 px-0 sidebar">
                    <div class="d-flex flex-column p-3">
                        <h5 class="text-white mb-4">
                            <i class="fas fa-hotel"></i> Hotel System
                        </h5>
                        
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                                   href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                                </a>
                            </li>
                            
                            @if(auth()->user()->canManageRooms())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}" 
                                   href="{{ route('rooms.manage') }}">
                                    <i class="fas fa-bed me-2"></i> Kelola Kamar
                                </a>
                            </li>
                            @endif
                            
                            @if(auth()->user()->canViewCleaning())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('cleaning.*') ? 'active' : '' }}" 
                                   href="{{ route('cleaning.index') }}">
                                    <i class="fas fa-broom me-2"></i> Cleaning
                                    @php
                                        $pendingCount = \App\Models\CleaningNotification::where('status', 'pending')->count();
                                    @endphp
                                    @if($pendingCount > 0)
                                        <span class="badge bg-danger badge-notification">{{ $pendingCount }}</span>
                                    @endif
                                </a>
                            </li>
                            @endif
                            
                            @if(auth()->user()->isSuperAdmin())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('users.*') && !request()->routeIs('users.profile') ? 'active' : '' }}" 
                                   href="{{ route('users.index') }}">
                                    <i class="fas fa-users me-2"></i> Kelola User
                                </a>
                            </li>
                            @endif
                            
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}" 
                                   href="{{ route('profile') }}">
                                    <i class="fas fa-user-cog me-2"></i> Profile
                                </a>
                            </li>
                        </ul>
                        
                        <hr class="text-white">
                        
                        <div class="mt-auto">
                            <div class="text-white-50 mb-2">
                                <small>{{ auth()->user()->name }}</small><br>
                                <small><strong>{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</strong></small>
                            </div>
                            <a class="nav-link text-danger" href="#" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="col-md-9 col-lg-10 content">
                    <main class="p-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        @yield('content')
                    </main>
                </div>
            </div>
        </div>
        @else
        <main class="py-4">
            @yield('content')
        </main>
        @endauth
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