@extends('layouts.app')

@section('title', 'Student paneli')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Xush kelibsiz, {{ Auth::user()->name }}!</h1>
            <p class="text-muted">Sizning shaxsiy kabinetingiz</p>
        </div>
    </div>

    @if($nextInterview)
    <!-- Keyingi intervyu -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">⏰ Keyingi intervyu</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4>{{ $nextInterview->title }}</h4>
                            <p><strong>O'qituvchi:</strong> {{ $nextInterview->teacher->name }}</p>
                            <p><strong>Sana:</strong> {{ $nextInterview->scheduled_at->format('d.m.Y H:i') }}</p>
                            <p><strong>Davomiylik:</strong> {{ $nextInterview->duration }} daqiqa</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="countdown-timer" data-time="{{ $nextInterview->scheduled_at }}">
                                <h3 id="countdown"></h3>
                            </div>
                            <a href="{{ route('student.interviews.show', $nextInterview) }}" class="btn btn-primary mt-3">
                                Tayyorlanish
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <!-- Statistika -->
        <div class="col-md-3 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6>Jami intervyular</h6>
                    <h2>{{ $myInterviews }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6>Yakunlangan</h6>
                    <h2>{{ $completedInterviews }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6>O'rtacha ball</h6>
                    <h2>{{ number_format($averageScore, 1) ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6>Kutilayotgan</h6>
                    <h2>{{ $upcomingInterviews->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Kutilayotgan intervyular -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-warning">
                    <h5 class="mb-0">📅 Kutilayotgan intervyular</h5>
                </div>
                <div class="card-body">
                    @forelse($upcomingInterviews as $interview)
                        <div class="alert alert-info">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $interview->title }}</strong>
                                    <p class="mb-0 small">O'qituvchi: {{ $interview->teacher->name }}</p>
                                    <small>{{ $interview->scheduled_at->format('d.m.Y H:i') }}</small>
                                </div>
                                <a href="{{ route('student.interviews.show', $interview) }}" class="btn btn-sm btn-primary">
                                    Ko'rish
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Kutilayotgan intervyular yo'q</p>
                    @endforelse
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
                    @forelse($pendingInvitations as $invitation)
                        <div class="alert alert-secondary">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $invitation->interview->title }}</strong>
                                    <p class="mb-0 small">O'qituvchi: {{ $invitation->interview->teacher->name }}</p>
                                </div>
                                <div>
                                    <form action="{{ route('student.invitations.accept', $invitation) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">✓</button>
                                    </form>
                                    <form action="{{ route('student.invitations.decline', $invitation) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">✗</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Yangi takliflar yo'q</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Oxirgi natijalar -->
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">📊 Oxirgi natijalar</h5>
                </div>
                <div class="card-body">
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
                                    <td>{{ $result->teacher->name }}</td>
                                    <td>{{ $result->scheduled_at->format('d.m.Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $result->score >= 70 ? 'success' : ($result->score >= 50 ? 'warning' : 'danger') }}">
                                            {{ $result->score }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('student.interviews.show', $result) }}" class="btn btn-sm btn-info">
                                            Batafsil
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@if($nextInterview)
<script>
// Countdown timer
function updateCountdown() {
    const interviewTime = new Date('{{ $nextInterview->scheduled_at }}').getTime();
    const now = new Date().getTime();
    const distance = interviewTime - now;

    if (distance < 0) {
        document.getElementById('countdown').innerHTML = "Vaqt keldi!";
        return;
    }

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    document.getElementById('countdown').innerHTML = 
        (days > 0 ? days + "d " : "") + 
        hours + "s " + 
        minutes + "d " + 
        seconds + "s";
}

setInterval(updateCountdown, 1000);
updateCountdown();
</script>
@endif
@endsection