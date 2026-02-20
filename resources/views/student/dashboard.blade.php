@extends('layouts.app')

@section('title', 'Student paneli')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!-- TO'G'RILANGAN: Auth::user() ishlatish -->
            <h1>Xush kelibsiz, {{ Auth::user()->name }}!</h1>
            <p>Email: {{ Auth::user()->email }}</p>
            <p>Rol: Student</p>
        </div>
    </div>

    <!-- Keyingi intervyu -->
    @if(isset($nextInterview) && $nextInterview)
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">⏰ Keyingi intervyu</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4>{{ $nextInterview->title }}</h4>
                            <p><strong>O'qituvchi:</strong> {{ $nextInterview->teacher->name ?? 'Noma\'lum' }}</p>
                            <p><strong>Sana:</strong> {{ $nextInterview->scheduled_at?->format('d.m.Y H:i') ?? 'Belgilanmagan' }}</p>
                            <p><strong>Davomiylik:</strong> {{ $nextInterview->duration ?? 30 }} daqiqa</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('student.interviews.show', $nextInterview) }}" class="btn btn-primary">
                                <i class="fas fa-eye"></i> Ko'rish
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Statistika kartochkalari -->
    <div class="row mt-4">
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
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6>O'rtacha ball</h6>
                    <h2>{{ number_format($averageScore ?? 0, 1) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6>Kutilayotgan</h6>
                    <h2>{{ isset($upcomingInterviews) ? $upcomingInterviews->count() : 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Kutilayotgan intervyular -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-warning">
                    <h5 class="mb-0">📅 Kutilayotgan intervyular</h5>
                </div>
                <div class="card-body">
                    @if(isset($upcomingInterviews) && $upcomingInterviews->count() > 0)
                        @foreach($upcomingInterviews as $interview)
                            <div class="alert alert-info">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>{{ $interview->title }}</strong>
                                        <p class="mb-0 small">O'qituvchi: {{ $interview->teacher->name ?? 'Noma\'lum' }}</p>
                                        <small>{{ $interview->scheduled_at?->format('d.m.Y H:i') }}</small>
                                    </div>
                                    <a href="{{ route('student.interviews.show', $interview) }}" class="btn btn-sm btn-primary">
                                        Ko'rish
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Kutilayotgan intervyular yo'q</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Takliflar -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">📨 Yangi takliflar</h5>
                </div>
                <div class="card-body">
                    @if(isset($pendingInvitations) && $pendingInvitations->count() > 0)
                        @foreach($pendingInvitations as $invitation)
                            <div class="alert alert-secondary">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>{{ $invitation->interview->title ?? 'Noma\'lum' }}</strong>
                                        <p class="mb-0 small">O'qituvchi: {{ $invitation->interview->teacher->name ?? 'Noma\'lum' }}</p>
                                    </div>
                                    <div>
                                        <form action="{{ route('student.invitations.accept', $invitation) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="Qabul qilish">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('student.invitations.decline', $invitation) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" title="Rad etish">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Yangi takliflar yo'q</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Oxirgi natijalar -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">📊 Oxirgi natijalar</h5>
                </div>
                <div class="card-body">
                    @if(isset($recentResults) && $recentResults->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Mavzu</th>
                                        <th>O'qituvchi</th>
                                        <th>Sana</th>
                                        <th>Ball</th>
                                        <th>Amallar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentResults as $result)
                                    <tr>
                                        <td>{{ $result->title }}</td>
                                        <td>{{ $result->teacher->name ?? 'Noma\'lum' }}</td>
                                        <td>{{ $result->scheduled_at?->format('d.m.Y') ?? 'N/A' }}</td>
                                        <td>
                                            @if($result->score)
                                                <span class="badge bg-{{ $result->score >= 70 ? 'success' : ($result->score >= 40 ? 'warning' : 'danger') }}">
                                                    {{ $result->score }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('student.interviews.show', $result) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Hali natijalar mavjud emas</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection