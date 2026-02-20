@extends('layouts.app')

@section('title', 'So\'rov detallari')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.teacher-requests.index') }}">So'rovlar</a></li>
                    <li class="breadcrumb-item active">#{{ $teacherRequest->id }} - {{ $teacherRequest->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">So'rov ma'lumotlari</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th style="width: 200px;">To'liq ism:</th>
                            <td>{{ $teacherRequest->name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $teacherRequest->email }}</td>
                        </tr>
                        <tr>
                            <th>Telefon:</th>
                            <td>{{ $teacherRequest->phone }}</td>
                        </tr>
                        <tr>
                            <th>Ish staji:</th>
                            <td>{{ $teacherRequest->experience_years }}</td>
                        </tr>
                        <tr>
                            <th>Qo'shimcha ma'lumot:</th>
                            <td>{{ $teacherRequest->notes ?? 'Yo\'q' }}</td>
                        </tr>
                        <tr>
                            <th>So'rov sanasi:</th>
                            <td>{{ $teacherRequest->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge bg-{{ $teacherRequest->status_color }}">
                                    {{ $teacherRequest->status_name }}
                                </span>
                            </td>
                        </tr>
                        @if($teacherRequest->approved_at)
                        <tr>
                            <th>Tasdiqlangan sana:</th>
                            <td>{{ $teacherRequest->approved_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Tasdiqlagan:</th>
                            <td>{{ $teacherRequest->approver->name ?? 'Noma\'lum' }}</td>
                        </tr>
                        @endif
                    </table>

                    <div class="mt-3">
                        <a href="{{ route('admin.teacher-requests.download', $teacherRequest) }}" 
                           class="btn btn-success" target="_blank">
                            <i class="fas fa-download"></i> Hujjatni ko'rish
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            @if($teacherRequest->status === 'pending')
            <div class="card">
                <div class="card-header bg-warning">
                    <h5 class="mb-0">So'rovni ko'rib chiqish</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#approveModal">
                            <i class="fas fa-check"></i> Tasdiqlash
                        </button>
                        
                        <button type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="fas fa-times"></i> Rad etish
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <div class="card mt-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Eslatma</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>Hujjatni tekshirib chiqing</li>
                        <li>Ma'lumotlarni tasdiqlang</li>
                        <li>So'rovni tasdiqlash yoki rad etish</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tasdiqlash Modal -->
@if($teacherRequest->status === 'pending')
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.teacher-requests.approve', $teacherRequest) }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">So'rovni tasdiqlash</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>{{ $teacherRequest->name }}</strong> ni o'qituvchi sifatida tasdiqlaysizmi?</p>
                    <p>Tasdiqlashdan so'ng foydalanuvchi o'qituvchi sifatida tizimga kira oladi.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bekor qilish</button>
                    <button type="submit" class="btn btn-success">Tasdiqlash</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Rad etish Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.teacher-requests.reject', $teacherRequest) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">So'rovni rad etish</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Rad etish sababi</label>
                        <textarea name="rejection_reason" id="rejection_reason" 
                                  class="form-control" rows="3" required></textarea>
                        <small class="text-muted">Bu sabab foydalanuvchiga ko'rsatiladi</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bekor qilish</button>
                    <button type="submit" class="btn btn-danger">Rad etish</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection