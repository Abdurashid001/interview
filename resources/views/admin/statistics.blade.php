@extends('layouts.app')

@section('title', 'Statistika')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1>Statistika</h1>
            <p class="text-muted">Tizim statistikasi</p>
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
                            <h2 class="mb-0">{{ $totalUsers ?? 0 }}</h2>
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
                            <h6 class="card-title">Studentlar</h6>
                            <h2 class="mb-0">{{ $totalStudents ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-user-graduate fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">O'qituvchilar</h6>
                            <h2 class="mb-0">{{ $totalTeachers ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-chalkboard-teacher fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Adminlar</h6>
                            <h2 class="mb-0">{{ $totalAdmins ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-user-cog fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Intervyu statistikasi -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Intervyu statistikasi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3>{{ $totalInterviews ?? 0 }}</h3>
                                <p class="text-muted">Jami intervyular</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3>{{ $completedInterviews ?? 0 }}</h3>
                                <p class="text-muted">Yakunlangan</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3>{{ $scheduledInterviews ?? 0 }}</h3>
                                <p class="text-muted">Rejalashtirilgan</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3>{{ $pendingInterviews ?? 0 }}</h3>
                                <p class="text-muted">Kutilayotgan</p>
                            </div>
                        </div>
                    </div>
                    @if(isset($averageScore))
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <strong>O'rtacha ball:</strong> {{ number_format($averageScore, 1) }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Diagrammalar -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Foydalanuvchilar taqsimoti</h5>
                </div>
                <div class="card-body">
                    <canvas id="usersChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Intervyular taqsimoti</h5>
                </div>
                <div class="card-body">
                    <canvas id="interviewsChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Oxirgi foydalanuvchilar -->
    @if(isset($recentUsers))
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">Oxirgi ro'yxatdan o'tganlar</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Ism</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Sana</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentUsers as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'teacher' ? 'success' : 'info') }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at?->format('d.m.Y') ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
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
            type: 'pie',
            data: {
                labels: ['Studentlar', 'O\'qituvchilar', 'Adminlar'],
                datasets: [{
                    data: [
                        {{ $totalStudents ?? 0 }},
                        {{ $totalTeachers ?? 0 }},
                        {{ $totalAdmins ?? 0 }}
                    ],
                    backgroundColor: ['#0dcaf0', '#198754', '#ffc107']
                }]
            }
        });
    }

    // Intervyular diagrammasi
    const interviewsCtx = document.getElementById('interviewsChart')?.getContext('2d');
    if (interviewsCtx) {
        new Chart(interviewsCtx, {
            type: 'doughnut',
            data: {
                labels: ['Yakunlangan', 'Rejalashtirilgan', 'Kutilayotgan'],
                datasets: [{
                    data: [
                        {{ $completedInterviews ?? 0 }},
                        {{ $scheduledInterviews ?? 0 }},
                        {{ $pendingInterviews ?? 0 }}
                    ],
                    backgroundColor: ['#198754', '#0dcaf0', '#ffc107']
                }]
            }
        });
    }
});
</script>
@endpush