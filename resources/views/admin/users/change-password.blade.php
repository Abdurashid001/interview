@extends('layouts.app')

@section('title', 'Parolni o\'zgartirish')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Foydalanuvchilar</a></li>
                    <li class="breadcrumb-item active">{{ $user->name }} - Parolni o'zgartirish</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="fas fa-key"></i> {{ $user->name }} parolini o'zgartirish</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Foydalanuvchi:</strong> {{ $user->name }} ({{ $user->email }})
                        <br>
                        <strong>Rol:</strong> 
                        <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'teacher' ? 'success' : 'info') }}">
                            {{ $user->role == 'admin' ? 'Admin' : ($user->role == 'teacher' ? 'O\'qituvchi' : 'Student') }}
                        </span>
                    </div>

                    <form action="{{ route('admin.users.update-password', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="password" class="form-label">Yangi parol <span class="text-danger">*</span></label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required>
                            <small class="text-muted">Kamida 8 belgidan iborat bo'lishi kerak</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Parolni tasdiqlang <span class="text-danger">*</span></label>
                            <input type="password" 
                                   class="form-control @error('password_confirmation') is-invalid @enderror" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Diqqat!</strong> Parol o'zgartirilgach, foydalanuvchi yangi parol bilan kirishi kerak bo'ladi.
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save"></i> Parolni saqlash
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Bekor qilish
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection