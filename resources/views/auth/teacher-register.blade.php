@extends('layouts.app')

@section('title', 'O\'qituvchi ro\'yxatdan o\'tish')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">O'qituvchi ro'yxatdan o'tish</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        O'qituvchi bo'lish uchun quyidagi formani to'ldiring. So'rovingiz admin tomonidan tekshirilgach, tasdiqlash haqida xabar olasiz.
                    </div>

                    <form method="POST" action="{{ route('teacher-request.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">To'liq ism</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Telefon</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" required>
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="experience_years" class="form-label">Ish staji</label>
                            <input type="text" class="form-control @error('experience_years') is-invalid @enderror" 
                                   id="experience_years" name="experience_years" value="{{ old('experience_years') }}" 
                                   placeholder="Masalan: 5 yil" required>
                            @error('experience_years') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="document" class="form-label">Ish stajini tasdiqlovchi hujjat (PDF)</label>
                            <input type="file" class="form-control @error('document') is-invalid @enderror" 
                                   id="document" name="document" accept=".pdf" required>
                            <small class="text-muted">Faqat PDF format, maksimal 5MB</small>
                            @error('document') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Parol</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Parolni tasdiqlang</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Qo'shimcha ma'lumot (ixtiyoriy)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <h7 style="color: red;">Sizning so'rovingiz admin tomonidan tasdiqlansagina ro'yxatdan o'ta olasiz!</h7>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> So'rov yuborish
                        </button>
                        <a href="{{ route('register') }}" class="btn btn-secondary">
                            Orqaga
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection