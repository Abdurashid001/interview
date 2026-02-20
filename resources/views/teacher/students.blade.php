@extends('layouts.app')

@section('title', 'Studentlar ro\'yxati')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <h1>Studentlar ro'yxati</h1>
            <p class="text-muted">Barcha studentlar</p>
        </div>
        <div class="col-md-4 text-end">
            <input type="text" id="searchInput" class="form-control" placeholder="Qidirish...">
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Studentlar</h5>
                </div>
                <div class="card-body">
                    @if($students->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover" id="studentsTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Ism</th>
                                        <th>Email</th>
                                        <th>Telefon</th>
                                        <th>Ro'yxatdan o'tgan</th>
                                        <th>Intervyular soni</th>
                                        <th>Amallar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                    <tr>
                                        <td>{{ $student->id }}</td>
                                        <td>
                                            <strong>{{ $student->name }}</strong>
                                        </td>
                                        <td>{{ $student->email }}</td>
                                        <td>{{ $student->phone ?? 'Ko\'rsatilmagan' }}</td>
                                        <td>
                                            <!-- TO'G'RILANGAN QATOR: null tekshirish -->
                                            @if($student->created_at)
                                                {{ $student->created_at->format('d.m.Y') }}
                                            @else
                                                <span class="text-muted">Noma'lum</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $student->interviews_count ?? 0 }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-info" title="Ko'rish">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if(method_exists($students, 'links'))
                            <div class="d-flex justify-content-center mt-4">
                                {{ $students->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users-slash fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Studentlar mavjud emas</h5>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Qidiruv funksiyasi
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let searchText = this.value.toLowerCase();
        let tableRows = document.querySelectorAll('#studentsTable tbody tr');
        
        tableRows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchText) ? '' : 'none';
        });
    });
</script>
@endpush
@endsection