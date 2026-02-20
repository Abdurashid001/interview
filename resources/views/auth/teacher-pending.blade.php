@extends('layouts.app')

@section('title', 'So\'rov qabul qilindi')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h4 class="mb-0">So'rov qabul qilindi</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-clock fa-5x text-warning"></i>
                    </div>
                    
                    <h3>So'rovingiz tekshirilmoqda</h3>
                    
                    <p class="lead">
                        Sizning so'rovingiz muvaffaqiyatli qabul qilindi va admin tomonidan tekshirilmoqda.
                    </p>
                    
                    <p>
                        <strong>Keyingi qadamlar:</strong>
                    </p>
                    
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success"></i>
                            Admin hujjatlaringizni tekshiradi
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success"></i>
                            Tasdiqlashdan so'ng email orqali xabar olasiz
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success"></i>
                            Keyin tizimga kirishingiz mumkin bo'ladi
                        </li>
                    </ul>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Bu jarayon odatda 24-48 soat davom etadi.
                    </div>
                    
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        Kirish sahifasiga qaytish
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection