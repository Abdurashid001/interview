@extends('layouts.app')

@section('title', 'Bildirishnomalar')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1>Bildirishnomalar</h1>
                <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-double"></i> Hammasini o'qildi deb belgilash
                    </button>
                </form>
            </div>
            <p class="text-muted">Sizga kelgan barcha bildirishnomalar</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Bildirishnomalar ro'yxati</h5>
                </div>
                <div class="card-body">
                    @if(isset($notifications) && $notifications->count() > 0)
                        <div class="list-group">
                            @foreach($notifications as $notification)
                                <div class="list-group-item list-group-item-action {{ !$notification->is_read ? 'list-group-item-primary' : '' }}">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <span class="notification-icon me-3" style="font-size: 1.5rem;">
                                                {{ $notification->icon }}
                                            </span>
                                            <div>
                                                <h6 class="mb-1">{{ $notification->title }}</h6>
                                                <p class="mb-1">{{ $notification->message }}</p>
                                                <small class="text-muted">
                                                    <i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            @if(!$notification->is_read)
                                                <form action="{{ route('notifications.read', $notification) }}" method="POST" class="me-2">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success" title="O'qildi deb belgilash">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            @if($notification->link)
                                                <a href="{{ $notification->link }}" class="btn btn-sm btn-outline-primary me-2" title="Ko'rish">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif
                                            
                                            <form action="{{ route('notifications.destroy', $notification) }}" method="POST" onsubmit="return confirm('Bu bildirishnomani o\'chirishni xohlaysizmi?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="O'chirish">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Bildirishnomalar yo'q</h5>
                            <p>Hozircha sizga hech qanday bildirishnoma kelmagan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .list-group-item-primary {
        background-color: #e8f4ff;
        border-left: 4px solid #0d6efd;
    }
    .notification-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        border-radius: 50%;
    }
</style>
@endpush