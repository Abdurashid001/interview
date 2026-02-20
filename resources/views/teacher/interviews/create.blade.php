@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Yangi intervyu yaratish</h1>

    <form action="{{ route('teacher.interviews.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Mavzu</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                   id="title" name="title" value="{{ old('title') }}" required>
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Tavsif</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="student_id" class="form-label">Student (ixtiyoriy)</label>
            <select class="form-control" id="student_id" name="student_id">
                <option value="">-- Tanlang (keyinroq) --</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                @endforeach
            </select>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="scheduled_at" class="form-label">Vaqti</label>
                <input type="datetime-local" class="form-control @error('scheduled_at') is-invalid @enderror" 
                       id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at') }}" required>
                @error('scheduled_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="duration" class="form-label">Davomiyligi (daqiqa)</label>
                <input type="number" class="form-control" id="duration" name="duration" value="30" min="15" max="180">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Davom etish</button>
        <a href="{{ route('teacher.interviews.index') }}" class="btn btn-secondary">Bekor qilish</a>
    </form>
</div>
@endsection