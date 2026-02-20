@extends('layouts.app')

@section('title', 'O\'qituvchi so\'rovlari')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1>O'qituvchi so'rovlari</h1>
            <p class="text-muted">Yangi o'qituvchi bo'lish uchun kelgan so'rovlar</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">So'rovlar ro'yxati</h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ism</th>
                                <th>Email</th>
                                <th>Telefon</th>
                                <th>Ish staji</th>
                                <th>Sana</th>
                                <th>Status</th>
                                <th>Amallar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $request)
                            <tr>
                                <td>{{ $request->id }}</td>
                                <td>{{ $request->name }}</td>
                                <td>{{ $request->email }}</td>
                                <td>{{ $request->phone }}</td>
                                <td>{{ $request->experience_years }}</td>
                                <td>{{ $request->created_at->format('d.m.Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $request->status_color }}">
                                        {{ $request->status_name }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.teacher-requests.show', $request) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Ko'rish
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <div class="d-flex justify-content-center">
                        {{ $requests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection