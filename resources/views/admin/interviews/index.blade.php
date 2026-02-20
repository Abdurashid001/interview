@extends('layouts.app')

@section('title', 'Intervyular boshqaruvi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1>Intervyular boshqaruvi</h1>
            <p class="text-muted">Barcha intervyular ro'yxati</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Intervyular</h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Mavzu</th>
                                <th>O'qituvchi</th>
                                <th>Student</th>
                                <th>Sana</th>
                                <th>Status</th>
                                <th>Ball</th>
                                <th>Amallar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($interviews as $interview)
                            <tr>
                                <td>{{ $interview->id }}</td>
                                <td>{{ $interview->title }}</td>
                                <td>{{ $interview->teacher->name ?? 'Noma\'lum' }}</td>
                                <td>{{ $interview->student->name ?? 'Tayinlanmagan' }}</td>
                                <td>{{ $interview->scheduled_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <span class="badge bg-{{ $interview->status_color }}">
                                        {{ $interview->status_name }}
                                    </span>
                                </td>
                                <td>
                                    @if($interview->score)
                                        <span class="badge bg-success">{{ $interview->score }}</span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.interviews.show', $interview) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <div class="d-flex justify-content-center">
                        {{ $interviews->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection