@extends('layouts.admin')

@section('title', 'Admin - Daftar User')

@section('content')
<div class="section-content section-dashboard-home" data-aos="fade-up">
    <div class="container-fluid">
        <div class="dashboard-heading">
            <h2 class="dashboard-title">User</h2>
            <p class="dashboard-subtitle">List of User</p>
        </div>
        <div class="dashboard-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <a href="{{ route('admin.user.create') }}" class="btn btn-primary mb-3">
                                <i class="fas fa-plus mr-2"></i>Tambah User Baru
                            </a>
                            <div class="table-responsive">
                                <table class="table table-hover w-100" id="crudTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Foto</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Roles</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('addon-script')
<script>
    var datatable = $('#crudTable').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        ajax: '{!! url()->current() !!}',
        columns: [
            { data: 'id', name: 'id' },
            { 
                data: 'photos', 
                name: 'photos',
                render: function(data, type, row) {
                    let photoUrl = data 
                        ? `{{ Storage::url('') }}${data}` 
                        : `https://ui-avatars.com/api/?name=${encodeURIComponent(row.name)}&background=0D8ABC&color=fff`;
                    
                    return `<img src="${photoUrl}" style="height: 40px; width: 40px; object-fit: cover;" class="rounded-circle border" />`;
                }
            },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'roles', name: 'roles' },
            { data: 'action', name: 'action', orderable: false, searchable: false, width: '15%' },
        ]
    });
</script>
@endpush