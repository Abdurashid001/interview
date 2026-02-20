@extends('layouts.app')

@section('title', 'Foydalanuvchilar boshqaruvi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <h1>Foydalanuvchilar boshqaruvi</h1>
            <p class="text-muted">Jami: {{ $users->count() }} ta foydalanuvchi</p>
        </div>
        <!-- <div class="col-md-4 text-end">
            <a href="{{ route('admin.users.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Yangi foydalanuvchi
            </a>
        </div> -->
    </div>

    <!-- Filter va qidiruv -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <select name="role" class="form-select">
                                <option value="">Barcha rollar</option>
                                <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student</option>
                                <option value="teacher" {{ request('role') == 'teacher' ? 'selected' : '' }}>O'qituvchi</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Ism yoki email bo'yicha qidirish..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Qidirish
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> Tozalash
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter tugmalari - TO'G'RI VERSIYA -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="btn-group" role="group">
            <a href="{{ route('admin.users.index') }}" 
               class="btn btn-outline-primary {{ !request('role') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Barchasi
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'student']) }}" 
               class="btn btn-outline-info {{ request('role') == 'student' ? 'active' : '' }}">
                <i class="fas fa-user-graduate"></i> Studentlar
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'teacher']) }}" 
               class="btn btn-outline-success {{ request('role') == 'teacher' ? 'active' : '' }}">
                <i class="fas fa-chalkboard-teacher"></i> O'qituvchilar
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" 
               class="btn btn-outline-danger {{ request('role') == 'admin' ? 'active' : '' }}">
                <i class="fas fa-user-cog"></i> Adminlar
            </a>
        </div>
    </div>
</div>

<!-- Select dropdown orqali filter (qo'shimcha) -->
<div class="row mb-3">
    <div class="col-md-3">
        <form method="GET" action="{{ route('admin.users.index') }}" id="filterForm">
            <select name="role" class="form-select" onchange="this.form.submit()">
                <option value="">Barcha rollar</option>
                <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student</option>
                <option value="teacher" {{ request('role') == 'teacher' ? 'selected' : '' }}>O'qituvchi</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </form>
    </div>
</div>
    <!-- Foydalanuvchilar jadvali -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Foydalanuvchilar ro'yxati</h5>
                </div>
                <div class="card-body">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Ism</th>
                                        <th>Email</th>
                                        <th>Rol</th>
                                        <th>Telefon</th>
                                        <th>Ro'yxatdan o'tgan</th>
                                        <th>Status</th>
                                        <th>Amallar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($user->avatar)
                                                    <img src="{{ asset('storage/' . $user->avatar) }}" 
                                                         class="rounded-circle me-2" 
                                                         style="width: 30px; height: 30px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                         style="width: 30px; height: 30px; font-size: 14px;">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <strong>{{ $user->name }}</strong>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'teacher' ? 'success' : 'info') }} p-2">
                                                <i class="fas fa-{{ $user->role == 'admin' ? 'crown' : ($user->role == 'teacher' ? 'chalkboard-teacher' : 'user-graduate') }}"></i>
                                                {{ $user->role == 'admin' ? 'Admin' : ($user->role == 'teacher' ? 'O\'qituvchi' : 'Student') }}
                                            </span>
                                        </td>
                                        <td>{{ $user->phone ?? '—' }}</td>
                                        <td>
                                            @if($user->created_at)
                                                <span class="badge bg-secondary">
                                                    <i class="far fa-calendar-alt"></i> {{ $user->created_at->format('d.m.Y') }}
                                                </span>
                                            @else
                                                <span class="badge bg-warning">Noma'lum</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->role == 'teacher')
                                                @if($user->is_approved)
                                                    <span class="badge bg-success" title="Tasdiqlangan">
                                                        <i class="fas fa-check-circle"></i> Tasdiqlangan
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning" title="Kutilmoqda">
                                                        <i class="fas fa-clock"></i> Kutilmoqda
                                                    </span>
                                                @endif
                                            @else
                                                <span class="badge bg-success" title="Faol">
                                                    <i class="fas fa-check"></i> Faol
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <!-- Ko'rish -->
                                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info" title="Ko'rish">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                <!-- Tahrirlash -->
                                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning" title="Tahrirlash">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <!-- PAROL O'ZGARTIRISH -->
                                                <a href="{{ route('admin.users.change-password', $user) }}" class="btn btn-sm btn-danger" title="Parolni o'zgartirish">
                                                    <i class="fas fa-key"></i>
                                                </a>
                                                
                                                <!-- O'qituvchini tasdiqlash -->
                                                @if($user->role == 'teacher' && !$user->is_approved)
                                                    <form action="{{ route('admin.users.approve', $user) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" title="Tasdiqlash">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                <!-- O'chirish (o'zini o'chira olmaydi) -->
                                                @if($user->id !== Auth::id())
                                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" 
                                                          onsubmit="return confirm('Bu foydalanuvchini o\'chirishni xohlaysizmi?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-dark" title="O'chirish">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if(method_exists($users, 'links'))
                            <div class="d-flex justify-content-center mt-4">
                                {{ $users->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users-slash fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">Foydalanuvchilar topilmadi</h4>
                            <p>Hozircha hech qanday foydalanuvchi mavjud emas.</p>
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
    .btn-group .btn {
        margin-right: 2px;
    }
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    .table td {
        vertical-align: middle;
    }
    .badge {
        font-size: 0.85em;
        padding: 0.5em 0.7em;
    }
    .pagination {
        margin-bottom: 0;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-submit filter when role changes
    document.querySelector('select[name="role"]')?.addEventListener('change', function() {
        this.form.submit();
    });
</script>
@endpush