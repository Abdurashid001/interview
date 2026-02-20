<!DOCTYPE html>
<html lang="uz">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Laravel App')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .notification-badge {
            position: relative;
            top: -10px;
            right: 5px;
            font-size: 0.7rem;
        }

        .dropdown-menu {
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-item {
            white-space: normal;
            width: 300px;
            padding: 10px;
        }

        .notification-item.unread {
            background-color: #f8f9fa;
        }

        .notification-icon {
            width: 30px;
            text-align: center;
            font-size: 1.2rem;
        }
    </style>
    @stack('styles')
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-graduation-cap"></i> CRM Tizimi
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                    <!-- Bildirishnomalar -->
                    <li class="nav-item dropdown">
                        <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            @php
                            $unreadCount = App\Models\Notification::where('user_id', Auth::id())
                            ->orWhereNull('user_id')
                            ->where('is_read', false)
                            ->count();
                            @endphp
                            @if($unreadCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge">
                                {{ $unreadCount }}
                            </span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                            @php
                            $notifications = App\Models\Notification::where('user_id', Auth::id())
                            ->orWhereNull('user_id')
                            ->latest()
                            ->take(5)
                            ->get();
                            @endphp

                            @forelse($notifications as $notification)
                            <li>
                                <a class="dropdown-item notification-item {{ !$notification->is_read ? 'unread' : '' }}"
                                    href="{{ $notification->link ?? '#' }}">
                                    <div class="d-flex align-items-center">
                                        <span class="notification-icon me-2">{{ $notification->icon }}</span>
                                        <div>
                                            <strong>{{ $notification->title }}</strong>
                                            <br>
                                            <small>{{ $notification->message }}</small>
                                            <br>
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            @if(!$loop->last)
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            @endif
                            @empty
                            <li><span class="dropdown-item text-muted">Bildirishnomalar yo'q</span></li>
                            @endforelse

                            @if($notifications->count() > 0)
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item text-center" href="{{ route('notifications.index') }}">
                                    <i class="fas fa-eye"></i> Barchasini ko'rish
                                </a>
                            </li>
                            <li>
                                <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-center text-success">
                                        <i class="fas fa-check-double"></i> Hammasini o'qildi deb belgilash
                                    </button>
                                </form>
                            </li>
                            @endif
                        </ul>
                    </li>

                    <!-- Foydalanuvchi menyusi -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                            <span class="badge bg-{{ Auth::user()->role == 'admin' ? 'danger' : (Auth::user()->role == 'teacher' ? 'success' : 'info') }}">
                                {{ Auth::user()->role == 'admin' ? 'Admin' : (Auth::user()->role == 'teacher' ? 'O\'qituvchi' : 'Student') }}
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard
                                </a>
                            </li>

                            @if(Auth::user()->role == 'teacher')
                            <li>
                                <a class="dropdown-item" href="{{ route('teacher.interviews.index') }}">
                                    <i class="fas fa-calendar-alt"></i> Intervyular
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('teacher.students') }}">
                                    <i class="fas fa-users"></i> Studentlar
                                </a>
                            </li>
                            @endif

                            @if(Auth::user()->role == 'student')
                            <li>
                                <a class="dropdown-item" href="{{ route('student.interviews.index') }}">
                                    <i class="fas fa-calendar-check"></i> Mening intervyularim
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('student.profile') }}">
                                    <i class="fas fa-id-card"></i> Profil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('student.courses') }}">
                                    <i class="fas fa-book"></i> Kurslar
                                </a>
                            </li>
                            @endif

                            @if(Auth::user()->role == 'admin')
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                    <i class="fas fa-user-cog"></i> Foydalanuvchilar
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.statistics') }}">
                                    <i class="fas fa-chart-bar"></i> Statistika
                                </a>
                            </li>
                            @endif

                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-id-card"></i> Profil
                                </a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt"></i> Chiqish
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt"></i> Kirish
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            <i class="fas fa-user-plus"></i> Ro'yxatdan o'tish
                        </a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Xabarlar -->
    @if(session('success'))
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    @if(session('info'))
    <div class="container mt-3">
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle"></i> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> Iltimos, xatoliklarni tekshiring:
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-light py-3 mt-5">
        <div class="container text-center">
            <p class="text-muted mb-0">
                <i class="far fa-copyright"></i> {{ date('Y') }} CRM Tizimi. Barcha huquqlar himoyalangan.
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Bildirishnomalarni avtomatik yopish
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                let bsAlert = new bootstrap.Alert(alert);
                setTimeout(function() {
                    bsAlert.close();
                }, 5000);
            });
        }, 100);

        // CSRF token sozlash
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @stack('scripts')
</body>

</html>