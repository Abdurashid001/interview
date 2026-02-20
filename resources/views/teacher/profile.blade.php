@extends('layouts.app')

@section('title', 'Mening profilim')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Mening profilim</h1>
            <p class="text-muted">Shaxsiy ma'lumotlaringiz</p>
        </div>
    </div>

    <div class="row">
        <!-- Chap qism - Rasm va asosiy ma'lumotlar -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-0">Profil rasmi</h5>
                </div>
                <div class="card-body text-center">
                    <!-- Avatar -->
                    <div class="mb-3">
                        @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                            alt="Avatar"
                            class="img-fluid rounded-circle border border-3 border-primary"
                            style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                        <div class="bg-primary text-white d-flex align-items-center justify-content-center rounded-circle mx-auto border border-3 border-primary"
                            style="width: 150px; height: 150px; font-size: 3rem;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        @endif
                    </div>

                    <!-- Rasm yuklash formasi -->
                    @if(isset($showUploadForm) && $showUploadForm)
                    <form action="{{ route('teacher.profile.upload-avatar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <input type="file" name="avatar" class="form-control form-control-sm" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-upload"></i> Rasm yuklash
                        </button>
                    </form>
                    @endif

                    <!-- Role badge -->
                    <div class="mt-3">
                        <span class="badge bg-success p-2">
                            <i class="fas fa-chalkboard-teacher"></i> O'qituvchi
                        </span>
                    </div>
                </div>
            </div>

            <!-- Tezkor ma'lumotlar -->
            <div class="card mt-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-clock"></i> Tezkor ma'lumotlar</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-calendar-check"></i> Jami intervyular</span>
                            <span class="badge bg-primary rounded-pill">{{ $totalInterviews ?? 0 }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-check-circle"></i> Yakunlangan</span>
                            <span class="badge bg-success rounded-pill">{{ $completedInterviews ?? 0 }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-clock"></i> Kutilayotgan</span>
                            <span class="badge bg-warning rounded-pill">{{ $pendingInterviews ?? 0 }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-users"></i> Studentlar</span>
                            <span class="badge bg-info rounded-pill">{{ $totalStudents ?? 0 }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- O'ng qism - Profil ma'lumotlari -->
        <div class="col-md-8">
            <!-- Asosiy ma'lumotlar -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-user-circle"></i> Shaxsiy ma'lumotlar</h5>
                    <a href="{{ route('teacher.profile.edit') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-edit"></i> Tahrirlash
                    </a>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 200px;"><i class="fas fa-user"></i> To'liq ism:</th>
                            <td>{{ Auth::user()->name }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-envelope"></i> Email:</th>
                            <td>{{ Auth::user()->email }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-phone"></i> Telefon:</th>
                            <td>{{ Auth::user()->phone ?? 'Ko\'rsatilmagan' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-briefcase"></i> Ish staji:</th>
                            <td>{{ Auth::user()->experience_years ?? 'Ko\'rsatilmagan' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-calendar-alt"></i> Ro'yxatdan o'tgan:</th>
                            <td>{{ Auth::user()->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-check-circle"></i> Tasdiqlangan:</th>
                            <td>
                                @if(Auth::user()->is_approved)
                                <span class="badge bg-success">Tasdiqlangan</span>
                                <small class="text-muted ms-2">
                                    @if(Auth::user()->approved_at)
                                    {{ \Carbon\Carbon::parse(Auth::user()->approved_at)->format('d.m.Y') }}
                                    @endif
                                </small>
                                @else
                                <span class="badge bg-warning">Kutilmoqda</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Hujjatlar -->
            @if(Auth::user()->document_path)
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-file-pdf"></i> Hujjatlar</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Sizning ish stajingizni tasdiqlovchi hujjat
                    </div>
                    <a href="{{ asset('storage/' . Auth::user()->document_path) }}"
                        class="btn btn-success" target="_blank">
                        <i class="fas fa-download"></i> Hujjatni ko'rish
                    </a>
                </div>
            </div>
            @endif

            <!-- Parolni o'zgartirish -->
            <div class="card mb-4">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="fas fa-key"></i> Parolni o'zgartirish</h5>
                </div>
                <div class="card-body">
                    @if(session('password_success'))
                    <div class="alert alert-success">{{ session('password_success') }}</div>
                    @endif

                    <form action="{{ route('teacher.profile.change-password') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Joriy parol</label>
                                <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                                @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Yangi parol</label>
                                <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" required>
                                @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Parolni tasdiqlang</label>
                                <input type="password" name="new_password_confirmation" class="form-control" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Parolni yangilash
                        </button>
                    </form>
                </div>
            </div>

            <!-- Oxirgi intervyular -->
            @if(isset($recentInterviews) && $recentInterviews->count() > 0)
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Oxirgi intervyular</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Mavzu</th>
                                    <th>Student</th>
                                    <th>Sana</th>
                                    <th>Status</th>
                                    <th>Ball</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentInterviews as $interview)
                                <tr>
                                    <td>{{ Str::limit($interview->title, 30) }}</td>
                                    <td>{{ $interview->student->name ?? 'N/A' }}</td>
                                    <td>{{ $interview->scheduled_at->format('d.m.Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $interview->status_color }}">
                                            {{ $interview->status_name }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($interview->score)
                                        <span class="badge bg-{{ $interview->score >= 70 ? 'success' : 'warning' }}">
                                            {{ $interview->score }}
                                        </span>
                                        @else
                                        -
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection