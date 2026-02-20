@extends('layouts.app')

@section('title', 'Mening intervyularim')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Mening intervyularim</h1>
        </div>
    </div>

    <!-- Takliflar bo'limi -->
    @if(isset($invitations) && $invitations->count() > 0)
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h4>📨 Yangi takliflar ({{ $invitations->count() }})</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mavzu</th>
                                    <th>O'qituvchi</th>
                                    <th>Sana</th>
                                    <th>Davomiylik</th>
                                    <th>Xabar</th>
                                    <th>Amallar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invitations as $invitation)
                                <tr>
                                    <td>{{ $invitation->interview->title }}</td>
                                    <td>{{ $invitation->interview->teacher->name }}</td>
                                    <td>{{ $invitation->interview->scheduled_at->format('d.m.Y H:i') }}</td>
                                    <td>{{ $invitation->interview->duration }} daqiqa</td>
                                    <td>{{ $invitation->message ?? 'Xabar yo\'q' }}</td>
                                    <td>
                                        <form action="{{ route('student.invitations.accept', $invitation) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">✅ Qabul qilish</button>
                                        </form>
                                        <form action="{{ route('student.invitations.decline', $invitation) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">❌ Rad etish</button>
                                        </form>
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
    @endif

    <!-- Rejalashtirilgan intervyular -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4>📅 Rejalashtirilgan intervyular</h4>
                </div>
                <div class="card-body">
                    @if($interviews->where('status', 'scheduled')->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Mavzu</th>
                                        <th>O'qituvchi</th>
                                        <th>Sana</th>
                                        <th>Davomiylik</th>
                                        <th>Status</th>
                                        <th>Amallar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($interviews->where('status', 'scheduled') as $interview)
                                    <tr>
                                        <td>{{ $interview->title }}</td>
                                        <td>{{ $interview->teacher->name }}</td>
                                        <td>{{ $interview->scheduled_at->format('d.m.Y H:i') }}</td>
                                        <td>{{ $interview->duration }} daqiqa</td>
                                        <td>
                                            <span class="badge bg-info">Rejalashtirilgan</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('student.interviews.show', $interview) }}" class="btn btn-primary btn-sm">
                                                👁 Ko'rish
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Rejalashtirilgan intervyular yo'q</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Yakunlangan intervyular -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4>✅ Yakunlangan intervyular</h4>
                </div>
                <div class="card-body">
                    @if($interviews->where('status', 'completed')->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
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
                                    @foreach($interviews->where('status', 'completed') as $interview)
                                    <tr>
                                        <td>{{ $interview->title }}</td>
                                        <td>{{ $interview->teacher->name }}</td>
                                        <td>{{ $interview->scheduled_at->format('d.m.Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $interview->score >= 70 ? 'success' : ($interview->score >= 50 ? 'warning' : 'danger') }}">
                                                {{ $interview->score ?? 'Ball yo\'q' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('student.interviews.show', $interview) }}" class="btn btn-info btn-sm">
                                                📊 Natijalarni ko'rish
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Yakunlangan intervyular yo'q</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection