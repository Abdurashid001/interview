@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1>Admin Dashboard</h1>
            <p class="text-muted">Xush kelibsiz, {{ Auth::user()->name }}! Tizim statistikasi</p>
        </div>
    </div>

    <!-- Statistika kartochkalari -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Jami foydalanuvchilar</h6>
                            <h2 class="mb-0">{{ $totalUsers }}</h2>
                        </div>
                        <i class="fas fa-users fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">O'qituvchilar</h6>
                            <h2 class="mb-0">{{ $totalTeachers }}</h2>
                        </div>
                        <i class="fas fa-chalkboard-teacher fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Studentlar</h6>
                            <h2 class="mb-0">{{ $totalStudents }}</h2>
                        </div>
                        <i class="fas fa-user-graduate fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Kutilayotgan so'rovlar</h6>
                            <h2 class="mb-0">{{ $pendingTeacherRequests }}</h2>
                        </div>
                        <i class="fas fa-clock fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ikkinchi qator statistika -->
    <div class="row mb-4">
        <!-- <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Intervyular statistikasi</h5>
                </div>
                <div class="card-body">
                    <canvas id="interviewsChart" style="height: 200px;"></canvas>
                    <div class="row text-center mt-3">
                        <div class="col-4">
                            <h6>Jami</h6>
                            <p class="h4">{{ $totalInterviews }}</p>
                        </div>
                        <div class="col-4">
                            <h6>Yakunlangan</h6>
                            <p class="h4">{{ $completedInterviews }}</p>
                        </div>
                        <div class="col-4">
                            <h6>Kutilayotgan</h6>
                            <p class="h4">{{ $pendingInterviews }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Foydalanuvchilar</h5>
                </div>
                <div class="card-body">
                    <canvas id="usersChart" style="height: 200px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">Tezkor amallar</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.teacher-requests.index') }}" class="btn btn-warning">
                            <i class="fas fa-clock"></i> O'qituvchi so'rovlari 
                            @if($pendingTeacherRequests > 0)
                                <span class="badge bg-danger">{{ $pendingTeacherRequests }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-info">
                            <i class="fas fa-users"></i> Foydalanuvchilar
                        </a>
                        <a href="{{ route('admin.statistics') }}" class="btn btn-success">
                            <i class="fas fa-chart-bar"></i> Batafsil statistika
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Oxirgi so'rovlar va foydalanuvchilar -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Oxirgi o'qituvchi so'rovlari</h5>
                </div>
                <div class="card-body">
                    @if($recentTeacherRequests->count() > 0)
                        <div class="list-group">
                            @foreach($recentTeacherRequests as $request)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $request->name }}</h6>
                                            <small class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
                                        </div>
                                        <span class="badge bg-{{ $request->status_color }}">
                                            {{ $request->status_name }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('admin.teacher-requests.index') }}" class="btn btn-sm btn-primary">
                                Barchasini ko'rish
                            </a>
                        </div>
                    @else
                        <p class="text-muted">Yangi so'rovlar yo'q</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Oxirgi foydalanuvchilar</h5>
                </div>
                <div class="card-body">
                    @if($recentUsers->count() > 0)
                        <div class="list-group">
                            @foreach($recentUsers as $user)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $user->name }}</h6>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                        <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'teacher' ? 'success' : 'info') }}">
                                            {{ $user->role }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-primary">
                                Barchasini ko'rish
                            </a>
                        </div>
                    @else
                        <p class="text-muted">Foydalanuvchilar yo'q</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Foydalanuvchilar diagrammasi
    const usersCtx = document.getElementById('usersChart')?.getContext('2d');
    if (usersCtx) {
        new Chart(usersCtx, {
            type: 'doughnut',
            data: {
                labels: ['Admin', 'O\'qituvchi', 'Student'],
                datasets: [{
                    data: [{{ $totalAdmins }}, {{ $totalTeachers }}, {{ $totalStudents }}],
                    backgroundColor: ['#ffc107', '#198754', '#0dcaf0']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Intervyular diagrammasi
    const interviewsCtx = document.getElementById('interviewsChart')?.getContext('2d');
    if (interviewsCtx) {
        new Chart(interviewsCtx, {
            type: 'bar',
            data: {
                labels: ['Jami', 'Yakunlangan', 'Kutilayotgan'],
                datasets: [{
                    data: [{{ $totalInterviews }}, {{ $completedInterviews }}, {{ $pendingInterviews }}],
                    backgroundColor: ['#0dcaf0', '#198754', '#ffc107']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
});
</script>
@endpush