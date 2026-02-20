@extends('layouts.app')

@section('title', $interview->title . ' - Intervyu')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teacher.interviews.index') }}">Intervyular</a></li>
                    <li class="breadcrumb-item active">{{ $interview->title }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>{{ $interview->title }}</h3>
                </div>
                <div class="card-body">
                    <p><strong>Tavsif:</strong> {{ $interview->description ?? 'Tavsif mavjud emas' }}</p>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <p><strong>📅 Sana:</strong> {{ $interview->scheduled_at->format('d.m.Y H:i') }}</p>
                            <p><strong>⏱ Davomiyligi:</strong> {{ $interview->duration }} daqiqa</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>👤 Student:</strong> 
                                @if($interview->student)
                                    {{ $interview->student->name }} ({{ $interview->student->email }})
                                @else
                                    <span class="text-warning">Tayinlanmagan</span>
                                @endif
                            </p>
                            <p><strong>📊 Status:</strong> 
                                <span class="badge bg-{{ $interview->status_color }}">
                                    {{ $interview->status_name }}
                                </span>
                            </p>
                        </div>
                    </div>

                    @if($interview->score)
                    <div class="alert alert-info">
                        <strong>🎯 Umumiy ball:</strong> {{ $interview->score }} / 100
                    </div>
                    @endif

                    @if($interview->feedback)
                    <div class="alert alert-secondary">
                        <strong>📝 Feedback:</strong> {{ $interview->feedback }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Savollar bo'limi -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4>Savollar</h4>
                </div>
                <div class="card-body">
                    @if($interview->questions->count() > 0)
                        @foreach($interview->questions as $index => $question)
                        <div class="card mb-3">
                            <div class="card-header">
                                <strong>{{ $index + 1 }}. {{ $question->question }}</strong>
                            </div>
                            <div class="card-body">
                                <p><strong>Student javobi:</strong> 
                                    {{ $question->answer ?? 'Javob berilmagan' }}
                                </p>
                                
                                @if($question->points)
                                <p><strong>Ball:</strong> {{ $question->points }}</p>
                                @endif

                                @if($question->teacher_notes)
                                <p><strong>O'qituvchi izohi:</strong> {{ $question->teacher_notes }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted">Savollar mavjud emas</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Amallar paneli -->
            <div class="card">
                <div class="card-header">
                    <h4>Amallar</h4>
                </div>
                <div class="card-body">
                    @if($interview->status == 'pending' || $interview->status == 'scheduled')
                        <a href="{{ route('teacher.interviews.questions', $interview) }}" 
                           class="btn btn-warning w-100 mb-2">
                            ✏️ Savollarni tahrirlash
                        </a>
                        
                        <button type="button" class="btn btn-primary w-100 mb-2" 
                                data-bs-toggle="modal" data-bs-target="#inviteModal">
                            👥 Studentlarni taklif qilish
                        </button>
                    @endif

                    @if($interview->status == 'scheduled' && $interview->student)
                        <a href="{{ route('teacher.interviews.results', $interview) }}" 
                           class="btn btn-success w-100 mb-2">
                            📝 Natijalarni kiritish
                        </a>
                    @endif

                    @if($interview->status == 'completed')
                        <a href="{{ route('teacher.interviews.results', $interview) }}" 
                           class="btn btn-info w-100 mb-2">
                            📊 Natijalarni ko'rish
                        </a>
                    @endif

                    <a href="{{ route('teacher.interviews.index') }}" 
                       class="btn btn-secondary w-100">
                        ⬅️ Orqaga
                    </a>
                </div>
            </div>

            <!-- Statistika -->
            <div class="card mt-3">
                <div class="card-header">
                    <h4>Statistika</h4>
                </div>
                <div class="card-body">
                    <p><strong>Savollar soni:</strong> {{ $interview->questions->count() }}</p>
                    <p><strong>Javob berilgan:</strong> 
                        {{ $interview->questions->whereNotNull('answer')->count() }}
                    </p>
                    @if($interview->score)
                    <p><strong>O'rtacha ball:</strong> 
                        {{ round($interview->questions->avg('points'), 1) }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Invite Modal -->
<div class="modal fade" id="inviteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('teacher.interviews.invite', $interview) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Studentlarni taklif qilish</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Studentlar</label>
                        <select name="student_ids[]" class="form-control" multiple required>
                            @foreach(\App\Models\User::where('role', 'student')->get() as $student)
                                <option value="{{ $student->id }}">
                                    {{ $student->name }} ({{ $student->email }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Bir nechta student tanlash mumkin</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Xabar (ixtiyoriy)</label>
                        <textarea name="message" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bekor qilish</button>
                    <button type="submit" class="btn btn-primary">Taklif yuborish</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection