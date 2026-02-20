@extends('layouts.app')

@section('title', 'Mening intervyularim')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <h1>Mening intervyularim</h1>
            <p class="text-muted">Barcha intervyular ro'yxati</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('teacher.interviews.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Yangi intervyu
            </a>
        </div>
    </div>

    <!-- Statistika kartochkalari -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6>Jami intervyular</h6>
                    <h2>{{ $interviews->count() ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6>Yakunlangan</h6>
                    <h2>{{ $interviews->where('status', 'completed')->count() ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6>Kutilayotgan</h6>
                    <h2>{{ $interviews->where('status', 'pending')->count() ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6>Rejalashtirilgan</h6>
                    <h2>{{ $interviews->where('status', 'scheduled')->count() ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter va qidiruv -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('teacher.interviews.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">Barcha statuslar</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Kutilayotgan</option>
                                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Rejalashtirilgan</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Yakunlangan</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Qidirish..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="{{ route('teacher.interviews.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Tozalash
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Intervyular jadvali -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Intervyular ro'yxati</h5>
                </div>
                <div class="card-body">
                    @if(isset($interviews) && $interviews->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Mavzu</th>
                                        <th>Student</th>
                                        <th>Sana</th>
                                        <th>Vaqt</th>
                                        <th>Davomiylik</th>
                                        <th>Status</th>
                                        <th>Ball</th>
                                        <th>Amallar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($interviews as $interview)
                                    <tr>
                                        <td>{{ $interview->id }}</td>
                                        <td>
                                            <strong>{{ $interview->title }}</strong>
                                            @if($interview->description)
                                                <br><small class="text-muted">{{ Str::limit($interview->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($interview->student)
                                                {{ $interview->student->name }}
                                                <br><small class="text-muted">{{ $interview->student->email }}</small>
                                            @else
                                                <span class="text-warning">Tayinlanmagan</span>
                                            @endif
                                        </td>
                                        <td>{{ $interview->scheduled_at->format('d.m.Y') }}</td>
                                        <td>{{ $interview->scheduled_at->format('H:i') }}</td>
                                        <td>{{ $interview->duration }} daqiqa</td>
                                        <td>
                                            <span class="badge bg-{{ $interview->status_color }}">
                                                {{ $interview->status_name }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($interview->score)
                                                <span class="badge bg-{{ $interview->score >= 70 ? 'success' : ($interview->score >= 40 ? 'warning' : 'danger') }}">
                                                    {{ $interview->score }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('teacher.interviews.show', $interview) }}" 
                                                   class="btn btn-sm btn-info" title="Ko'rish">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                @if($interview->status == 'pending' || $interview->status == 'scheduled')
                                                    <a href="{{ route('teacher.interviews.questions', $interview) }}" 
                                                       class="btn btn-sm btn-warning" title="Savollarni tahrirlash">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                                
                                                @if($interview->status == 'scheduled' && $interview->student)
                                                    <a href="{{ route('teacher.interviews.results.show', $interview) }}" 
                                                       class="btn btn-sm btn-success" title="Natijalarni kiritish">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if(method_exists($interviews, 'links'))
                            <div class="d-flex justify-content-center mt-4">
                                {{ $interviews->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Intervyular mavjud emas</h5>
                            <p>Hozircha sizda hech qanday intervyu yo'q.</p>
                            <a href="{{ route('teacher.interviews.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Yangi intervyu yaratish
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table td {
        vertical-align: middle;
    }
    .btn-group .btn {
        margin-right: 2px;
    }
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    .badge {
        font-size: 0.9em;
        padding: 0.5em 0.7em;
    }
</style>
@endpush