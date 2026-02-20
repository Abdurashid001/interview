@extends('layouts.app')

@section('title', 'Bildirishnoma')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('notifications.index') }}">Bildirishnomalar</a></li>
                    <li class="breadcrumb-item active">Bildirishnoma #{{ $notification->id }}</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Bildirishnoma detallari</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <span class="display-1">{{ $notification->icon }}</span>
                    </div>
                    
                    <h3 class="text-center mb-3">{{ $notification->title }}</h3>
                    
                    <div class="alert alert-info">
                        {{ $notification->message }}
                    </div>
                    
                    <table class="table">
                        <tr>
                            <th style="width: 150px;">Turi:</th>
                            <td>
                                <span class="badge bg-{{ $notification->color }}">
                                    {{ $notification->type }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Yuborilgan sana:</th>
                            <td>{{ $notification->created_at->format('d.m.Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Holati:</th>
                            <td>
                                @if($notification->is_read)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check"></i> O'qilgan ({{ $notification->read_at->format('d.m.Y H:i') }})
                                    </span>
                                @else
                                    <span class="badge bg-warning">O'qilmagan</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('notifications.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Orqaga
                        </a>
                        
                        <div>
                            @if(!$notification->is_read)
                                <form action="{{ route('notifications.read', $notification) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check"></i> O'qildi deb belgilash
                                    </button>
                                </form>
                            @endif
                            
                            <form action="{{ route('notifications.destroy', $notification) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu bildirishnomani o\'chirishni xohlaysizmi?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> O'chirish
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection