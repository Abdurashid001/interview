@extends('layouts.app')

@section('title', 'Natijalar - ' . $interview->title)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teacher.interviews.index') }}">Intervyular</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teacher.interviews.show', $interview) }}">{{ $interview->title }}</a></li>
                    <li class="breadcrumb-item active">Natijalar</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Natijalarni kiritish - {{ $interview->title }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher.interviews.results.submit', $interview) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <h5>Student: <strong>{{ $interview->student->name ?? 'Tayinlanmagan' }}</strong></h5>
                            <p>Email: {{ $interview->student->email ?? 'Yo\'q' }}</p>
                            <p>Intervyu vaqti: {{ $interview->scheduled_at->format('d.m.Y H:i') }}</p>
                        </div>

                        <hr>

                        <h4 class="mb-3">Savollar va javoblar</h4>

                        @foreach($interview->questions as $index => $question)
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <strong>{{ $index + 1 }}. {{ $question->question }}</strong>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Student javobi:</label>
                                    <p class="border p-2 rounded bg-light">{{ $question->answer ?? 'Javob berilmagan' }}</p>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Ball (0-100):</label>
                                        <input type="number" 
                                               name="questions[{{ $question->id }}][points]" 
                                               class="form-control" 
                                               value="{{ $question->points }}"
                                               min="0" max="100">
                                    </div>
                                    <div class="col-md-8 mb-3">
                                        <label class="form-label">O'qituvchi izohi:</label>
                                        <textarea name="questions[{{ $question->id }}][teacher_notes]" 
                                                  class="form-control" 
                                                  rows="2">{{ $question->teacher_notes }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h5>Umumiy fikr</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Umumiy ball:</label>
                                        <input type="number" 
                                               name="score" 
                                               class="form-control" 
                                               value="{{ $interview->score }}"
                                               min="0" max="100">
                                    </div>
                                    <div class="col-md-8 mb-3">
                                        <label class="form-label">Feedback:</label>
                                        <textarea name="feedback" 
                                                  class="form-control" 
                                                  rows="3">{{ $interview->feedback }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('teacher.interviews.show', $interview) }}" class="btn btn-secondary">
                                ⬅️ Orqaga
                            </a>
                            <button type="submit" class="btn btn-success">
                                💾 Natijalarni saqlash
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>Ma'lumot</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Savollar soni
                            <span class="badge bg-primary rounded-pill">{{ $interview->questions->count() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Javob berilgan
                            <span class="badge bg-success rounded-pill">{{ $interview->questions->whereNotNull('answer')->count() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Javobsiz qolgan
                            <span class="badge bg-warning rounded-pill">{{ $interview->questions->whereNull('answer')->count() }}</span>
                        </li>
                    </ul>

                    <hr>

                    <div class="alert alert-info">
                        <h5>Qo'llanma:</h5>
                        <ol class="small">
                            <li>Har bir savolga ball qo'ying (0-100)</li>
                            <li>Izoh yozishingiz mumkin</li>
                            <li>Umumiy ballni hisoblang</li>
                            <li>Feedback yozing</li>
                            <li>"Natijalarni saqlash" tugmasini bosing</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection