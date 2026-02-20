@extends('layouts.app')

@section('title', $interview->title . ' - Intervyu')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.interviews.index') }}">Intervyular</a></li>
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
                            <p><strong>👨‍🏫 O'qituvchi:</strong> {{ $interview->teacher->name }}</p>
                            <p><strong>📊 Status:</strong> 
                                <span class="badge bg-{{ $interview->status_color }}">
                                    {{ $interview->status_name }}
                                </span>
                            </p>
                        </div>
                    </div>

                    @if($interview->status == 'completed' && $interview->score)
                    <div class="alert alert-info">
                        <h5>🎯 Umumiy ball: {{ $interview->score }} / 100</h5>
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
                        @if($interview->status == 'scheduled')
                            <!-- Javob berish formasi -->
                            <form action="{{ route('student.interviews.submit-answers', $interview) }}" method="POST">
                                @csrf
                                @foreach($interview->questions as $index => $question)
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <strong>{{ $index + 1 }}. {{ $question->question }}</strong>
                                    </div>
                                    <div class="card-body">
                                        <textarea name="answers[{{ $question->id }}]" 
                                                  class="form-control" 
                                                  rows="3"
                                                  placeholder="Javobingizni yozing...">{{ $question->answer }}</textarea>
                                    </div>
                                </div>
                                @endforeach
                                <button type="submit" class="btn btn-primary">Javoblarni saqlash</button>
                            </form>
                        @elseif($interview->status == 'completed')
                            <!-- Natijalarni ko'rish -->
                            @foreach($interview->questions as $index => $question)
                            <div class="card mb-3">
                                <div class="card-header">
                                    <strong>{{ $index + 1 }}. {{ $question->question }}</strong>
                                </div>
                                <div class="card-body">
                                    <p><strong>Sizning javobingiz:</strong> {{ $question->answer ?? 'Javob berilmagan' }}</p>
                                    
                                    @if($question->points)
                                    <p><strong>Ball:</strong> {{ $question->points }}</p>
                                    @endif

                                    @if($question->teacher_notes)
                                    <div class="alert alert-secondary">
                                        <strong>O'qituvchi izohi:</strong> {{ $question->teacher_notes }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach

                            @if($interview->feedback)
                            <div class="alert alert-info">
                                <h5>📝 O'qituvchi fikri:</h5>
                                <p>{{ $interview->feedback }}</p>
                            </div>
                            @endif
                        @endif
                    @else
                        <p class="text-muted">Savollar hali qo'shilmagan</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Ma'lumot paneli -->
            <div class="card">
                <div class="card-header">
                    <h4>Intervyu haqida</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Savollar soni
                            <span class="badge bg-primary rounded-pill">{{ $interview->questions->count() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Javob berilgan
                            <span class="badge bg-success rounded-pill">{{ $interview->questions->whereNotNull('answer')->count() }}</span>
                        </li>
                        @if($interview->status == 'completed')
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            To'plangan ball
                            <span class="badge bg-info rounded-pill">{{ $interview->questions->sum('points') }}</span>
                        </li>
                        @endif
                    </ul>
                </div>
                <div class="card-footer">
                    <a href="{{ route('student.interviews.index') }}" class="btn btn-secondary w-100">
                        ⬅️ Intervyular ro'yxatiga qaytish
                    </a>
                </div>
            </div>

            @if($interview->status == 'scheduled')
            <div class="card mt-3 border-warning">
                <div class="card-header bg-warning">
                    <h5>⚠️ Eslatma</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">Intervyu vaqti: <strong>{{ $interview->scheduled_at->format('d.m.Y H:i') }}</strong></p>
                    <p class="mb-0 mt-2">Davomiylik: <strong>{{ $interview->duration }} daqiqa</strong></p>
                    <hr>
                    <p class="text-muted small mb-0">Intervyu vaqtida tayyor bo'ling!</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection