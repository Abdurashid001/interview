@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $interview->title }} - Savollar</h1>

    <form action="{{ route('teacher.interviews.store-questions', $interview) }}" method="POST">
        @csrf

        <div id="questions-container">
            <div class="question-item mb-3">
                <label class="form-label">1-savol</label>
                <input type="text" name="questions[]" class="form-control" required>
            </div>
        </div>

        <button type="button" class="btn btn-secondary mb-3" onclick="addQuestion()">
            + Yangi savol qo'shish
        </button>

        <div>
            <button type="submit" class="btn btn-primary">Saqlash</button>
            <a href="{{ route('teacher.interviews.index') }}" class="btn btn-secondary">Keyinroq</a>
        </div>
    </form>
</div>

<script>
let questionCount = 1;

function addQuestion() {
    questionCount++;
    const html = `
        <div class="question-item mb-3">
            <label class="form-label">${questionCount}-savol</label>
            <input type="text" name="questions[]" class="form-control" required>
        </div>
    `;
    document.getElementById('questions-container').insertAdjacentHTML('beforeend', html);
}
</script>
@endsection