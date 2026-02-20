@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Admin Dashboard</h1>
            <p class="text-muted">Xush kelibsiz, {{ Auth::user()->name }}! Tizim statistikasi</p>
        </div>
    </div>

    <!-- Statistika kartochkalari -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Jami foydalanuvchilar</h6>
                            <h2 class="mb-0">{{ $totalUsers }}</h2>
                        </div>
                        <i class="fas fa-users fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">O'qituvchilar</h6>
                            <h2 class="mb-0">{{ $totalTeachers }}</h2>
                        </div>
                        <i class="fas fa-chalkboard-teacher fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Studentlar</h6>
                            <h2 class="mb-0">{{ $totalStudents }}</h2>
                        </div>
                        <i class="fas fa-user-graduate fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Intervyular</h6>
                            <h2 class="mb-0">{{ $totalInterviews }}</h2>
                        </div>
                        <i class="fas fa-calendar-check fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Asosiy kontent -->
    <div class="row">
        <!-- Oxirgi foydalanuvchilar -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">🆕 Oxirgi foydalanuvchilar</h5>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-primary">Barchasi</a>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($recentUsers as $user)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1">{{ $user->name }}</h6>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                    <div>
                                        <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'teacher' ? 'success' : 'info') }}">
                                            {{ $user->role }}
                                        </span>
                                        <small class="text-muted d-block">{{ $user->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Oxirgi intervyular -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">📊 Oxirgi intervyular</h5>
                    <a href="{{ route('admin.interviews.index') }}" class="btn btn-sm btn-primary">Barchasi</a>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($recentInterviews as $interview)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1">{{ $interview->title }}</h6>
                                        <small class="text-muted">
                                            {{ $interview->teacher->name }} → 
                                            {{ $interview->student->name ?? 'Student yo\'q' }}
                                        </small>
                                    </div>
                                    <div>
                                        <span class="badge bg-{{ $interview->status_color }}">
                                            {{ $interview->status_name }}
                                        </span>
                                        <small class="text-muted d-block">{{ $interview->scheduled_at->format('d.m.Y') }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tizim yangiliklari -->
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">📢 Tizim yangiliklari</h5>
                </div>
                <div class="card-body">
                    @foreach($systemUpdates as $update)
                        <div class="alert alert-info">
                            <strong>{{ $update['title'] }}</strong> - {{ $update['description'] }}
                            <small class="text-muted float-end">{{ $update['date']->diffForHumans() }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection