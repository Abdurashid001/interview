@extends('layouts.app')

@section('title', 'Mening kurslarim')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <h1>Mening kurslarim</h1>
            <p class="text-muted">Siz yozilgan barcha kurslar</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="#" class="btn btn-primary">
                <i class="fas fa-search"></i> Yangi kurs qidirish
            </a>
        </div>
    </div>

    <!-- Statistika -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6>Jami kurslar</h6>
                    <h2>{{ $totalCourses ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6>Yakunlangan</h6>
                    <h2>{{ $completedCourses ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6>Davom etayotgan</h6>
                    <h2>{{ $inProgressCourses ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6>Sertifikatlar</h6>
                    <h2>{{ $certificates ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Kurslar ro'yxati -->
    <div class="row">
        @if(isset($courses) && $courses->count() > 0)
            @foreach($courses as $course)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <!-- TO'G'RILANGAN: image property mavjudligini tekshirish -->
                    @if(property_exists($course, 'image') && $course->image)
                        <img src="{{ asset('storage/' . $course->image) }}" class="card-img-top" alt="{{ $course->title ?? 'Kurs' }}" style="height: 180px; object-fit: cover;">
                    @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 180px;">
                            <i class="fas fa-book-open fa-4x"></i>
                        </div>
                    @endif
                    
                    <div class="card-body">
                        <h5 class="card-title">{{ $course->title ?? 'Nomsiz kurs' }}</h5>
                        <p class="card-text text-muted">{{ isset($course->description) ? Str::limit($course->description, 80) : 'Tavsif mavjud emas' }}</p>
                        
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-info">
                                <i class="fas fa-user"></i> 
                                {{ isset($course->teacher) && isset($course->teacher->name) ? $course->teacher->name : 'Noma\'lum' }}
                            </span>
                            <span class="badge bg-{{ isset($course->progress) ? ($course->progress >= 80 ? 'success' : ($course->progress >= 40 ? 'warning' : 'secondary')) : 'secondary' }}">
                                {{ $course->progress ?? 0 }}% tugagan
                            </span>
                        </div>
                        
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar bg-{{ isset($course->progress) ? ($course->progress >= 80 ? 'success' : ($course->progress >= 40 ? 'warning' : 'info')) : 'info' }}" 
                                 role="progressbar" 
                                 style="width: {{ $course->progress ?? 0 }}%">
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="far fa-clock"></i> {{ $course->duration ?? 20 }} soat
                            </small>
                            <a href="#" class="btn btn-sm btn-primary">
                                @if(isset($course->progress) && $course->progress == 100)
                                    <i class="fas fa-redo"></i> Qayta o'qish
                                @elseif(isset($course->progress) && $course->progress > 0)
                                    <i class="fas fa-play"></i> Davom etish
                                @else
                                    <i class="fas fa-play"></i> Boshlash
                                @endif
                            </a>
                        </div>
                    </div>
                    
                    @if(isset($course->progress) && $course->progress == 100)
                        <div class="card-footer bg-success text-white text-center py-2">
                            <i class="fas fa-certificate"></i> Sertifikat olish
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        @else
            <div class="col-md-12">
                <div class="text-center py-5">
                    <i class="fas fa-book-open fa-5x text-muted mb-3"></i>
                    <h4 class="text-muted">Siz hali hech qanday kursga yozilmagansiz</h4>
                    <p class="text-muted">Yangi kurslarni ko'rish va o'qishni boshlash uchun pastdagi tugmani bosing.</p>
                    <a href="#" class="btn btn-primary btn-lg mt-3">
                        <i class="fas fa-search"></i> Kurslarni ko'rish
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection