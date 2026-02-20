@extends('layouts.app')

@section('title', 'O\'qituvchi paneli')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- TO'G'RILANGAN: Auth::user() ishlatish -->
            <h1>Xush kelibsiz, {{ Auth::user()->name }}!</h1>
            <p class="text-muted">Sizning shaxsiy dashboardingiz</p>
        </div>
    </div>

    <!-- Statistika kartochkalari -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6>Jami intervyular</h6>
                    <h2>{{ $myInterviews ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6>Yakunlangan</h6>
                    <h2>{{ $completedInterviews ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6>Kutilayotgan</h6>
                    <h2>{{ $upcomingInterviews ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6>Studentlar</h6>
                    <h2>{{ $totalStudents ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Bugungi intervyular -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-warning">
                    <h5 class="mb-0">📅 Bugungi intervyular</h5>
                </div>
                <div class="card-body">
                    @if(isset($schedule) && $schedule->count() > 0)
                        @foreach($schedule as $interview)
                            <div class="alert alert-info">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>{{ $interview->title }}</strong>
                                        <p class="mb-0 small">Student: {{ $interview->student->name ?? 'Yo\'q' }}</p>
                                    </div>
                                    <div>
                                        <span class="badge bg-primary">{{ $interview->scheduled_at?->format('H:i') ?? 'Vaqtsiz' }}</span>
                                        <a href="{{ route('teacher.interviews.show', $interview) }}" class="btn btn-sm btn-outline-primary ms-2">
                                            Ko'rish
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Bugun intervyular yo'q</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Kutilayotgan takliflar -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">📨 Kutilayotgan takliflar</h5>
                </div>
                <div class="card-body">
                    @if(isset($pendingInvitations) && $pendingInvitations->count() > 0)
                        @foreach($pendingInvitations as $invitation)
                            <div class="alert alert-secondary">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>{{ $invitation->student->name ?? 'Noma\'lum' }}</strong>
                                        <p class="mb-0 small">{{ $invitation->interview->title ?? 'Intervyu' }}</p>
                                    </div>
                                    <span class="badge bg-warning">Kutilmoqda</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Kutilayotgan takliflar yo'q</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Oxirgi intervyular -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">📊 Oxirgi intervyular</h5>
                </div>
                <div class="card-body">
                    @if(isset($recentInterviews) && $recentInterviews->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Mavzu</th>
                                        <th>Student</th>
                                        <th>Sana</th>
                                        <th>Ball</th>
                                        <th>Amallar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentInterviews as $interview)
                                        <tr>
                                            <td>{{ $interview->title }}</td>
                                            <td>{{ $interview->student->name ?? 'Yo\'q' }}</td>
                                            <td>{{ $interview->scheduled_at?->format('d.m.Y') ?? 'N/A' }}</td>
                                            <td>
                                                @if($interview->score)
                                                    <span class="badge bg-success">{{ $interview->score }}</span>
                                                @else
                                                    <span class="badge bg-secondary">Kutilmoqda</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('teacher.interviews.show', $interview) }}" class="btn btn-sm btn-info">
                                                    Ko'rish
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Oxirgi intervyular yo'q</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Bildirishnomalar -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">🔔 Bildirishnomalar</h5>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @if(isset($notifications) && $notifications->count() > 0)
                        @foreach($notifications as $notification)
                            <div class="alert alert-{{ $notification->type ?? 'info' }} alert-dismissible fade show" role="alert">
                                {{ $notification->message ?? 'Yangilik bor!' }}
                                <small class="d-block text-muted">{{ $notification->created_at?->diffForHumans() ?? 'Noma\'lum' }}</small>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Bildirishnomalar yo'q</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection