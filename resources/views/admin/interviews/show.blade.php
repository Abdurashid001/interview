@extends('layouts.app')

@section('title', 'Intervyu detallari')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.interviews.index') }}">Intervyular</a></li>
                    <li class="breadcrumb-item active">#{{ $interview->id }} - {{ $interview->title }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ $interview->title }}</h5>
                </div>
                <div class="card-body">
                    <p><strong>Tavsif:</strong> {{ $interview->description ?? 'Tavsif yo\'q' }}</p>
                    
                    <hr>
                    
                    <h5>Savollar va javoblar</h5>
                    @foreach($interview->questions as $index => $question)
                        <div class="card mt-2">
                            <div class="card-header">
                                <strong>{{ $index + 1 }}. {{ $question->question }}</strong>
                            </div>
                            <div class="card-body">
                                <p><strong>Javob:</strong> {{ $question->answer ?? 'Javob berilmagan' }}</p>
                                @if($question->points)
                                    <p><strong>Ball:</strong> {{ $question->points }}</p>
                                @endif
                                @if($question->teacher_notes)
                                    <p><strong>Izoh:</strong> {{ $question->teacher_notes }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Ma'lumotlar</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <strong>O'qituvchi:</strong> {{ $interview->teacher->name ?? 'Noma\'lum' }}
                        </li>
                        <li class="list-group-item">
                            <strong>Student:</strong> {{ $interview->student->name ?? 'Tayinlanmagan' }}
                        </li>
                        <li class="list-group-item">
                            <strong>Sana:</strong> {{ $interview->scheduled_at->format('d.m.Y H:i') }}
                        </li>
                        <li class="list-group-item">
                            <strong>Davomiylik:</strong> {{ $interview->duration }} daqiqa
                        </li>
                        <li class="list-group-item">
                            <strong>Status:</strong> 
                            <span class="badge bg-{{ $interview->status_color }}">
                                {{ $interview->status_name }}
                            </span>
                        </li>
                        @if($interview->score)
                        <li class="list-group-item">
                            <strong>Umumiy ball:</strong> {{ $interview->score }}
                        </li>
                        @endif
                        @if($interview->feedback)
                        <li class="list-group-item">
                            <strong>Feedback:</strong> {{ $interview->feedback }}
                        </li>
                        @endif
                    </ul>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.interviews.index') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-arrow-left"></i> Orqaga
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection