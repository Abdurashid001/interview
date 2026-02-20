@extends('layouts.app')

@section('title', 'Foydalanuvchi ma\'lumotlari')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Foydalanuvchilar</a></li>
                    <li class="breadcrumb-item active">{{ $user->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Profil rasmi</h5>
                </div>
                <div class="card-body text-center">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" 
                             class="img-fluid rounded-circle mb-3" 
                             style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded-circle mx-auto mb-3"
                             style="width: 150px; height: 150px; font-size: 3rem;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                    
                    <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'teacher' ? 'success' : 'info') }} p-2">
                        {{ $user->role == 'admin' ? 'Admin' : ($user->role == 'teacher' ? 'O\'qituvchi' : 'Student') }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Foydalanuvchi ma'lumotlari</h5>
                    <div>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-edit"></i> Tahrirlash
                        </a>
                        <a href="{{ route('admin.users.change-password', $user) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-key"></i> Parolni o'zgartirish
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th style="width: 200px;">ID:</th>
                            <td>{{ $user->id }}</td>
                        </tr>
                        <tr>
                            <th>Ism:</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Telefon:</th>
                            <td>{{ $user->phone ?? 'Ko\'rsatilmagan' }}</td>
                        </tr>
                        <tr>
                            <th>Rol:</th>
                            <td>
                                <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'teacher' ? 'success' : 'info') }}">
                                    {{ $user->role == 'admin' ? 'Admin' : ($user->role == 'teacher' ? 'O\'qituvchi' : 'Student') }}
                                </span>
                            </td>
                        </tr>
                        @if($user->role == 'teacher')
                        <tr>
                            <th>Tasdiqlangan:</th>
                            <td>
                                @if($user->is_approved)
                                    <span class="badge bg-success">Tasdiqlangan</span>
                                    <small class="text-muted ms-2">{{ $user->approved_at?->format('d.m.Y') }}</small>
                                @else
                                    <span class="badge bg-warning">Kutilmoqda</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Ish staji:</th>
                            <td>{{ $user->experience_years ?? 'Ko\'rsatilmagan' }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Ro'yxatdan o'tgan:</th>
                            <td>{{ $user->created_at?->format('d.m.Y H:i') ?? 'Noma\'lum' }}</td>
                        </tr>
                        <tr>
                            <th>Oxirgi tahrir:</th>
                            <td>{{ $user->updated_at?->diffForHumans() ?? 'Noma\'lum' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection