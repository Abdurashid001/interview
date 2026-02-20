@extends('layouts.app')

@section('title', 'Mening profilim')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Mening profilim</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th style="width: 150px;">Ism:</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Rol:</th>
                            <td>
                                <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'teacher' ? 'success' : 'info') }}">
                                    {{ $user->role == 'admin' ? 'Admin' : ($user->role == 'teacher' ? 'O\'qituvchi' : 'Student') }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Ro'yxatdan o'tgan:</th>
                            <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    </table>

                    <a href="{{ route('profile.edit') }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Tahrirlash
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection