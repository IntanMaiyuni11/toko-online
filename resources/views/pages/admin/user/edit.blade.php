@extends('layouts.admin')

@section('title', 'Admin - Edit User')

@section('content')
<div class="section-content section-dashboard-home" data-aos="fade-up">
    <div class="container-fluid">
        <div class="dashboard-heading">
            <h2 class="dashboard-title">User</h2>
            <p class="dashboard-subtitle">Edit "{{ $item->name }}" User</p>
        </div>
        <div class="dashboard-content">
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('admin.user.update', $item->id) }}" method="post" enctype="multipart/form-data">
                        @method('PUT') @csrf
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 text-center mb-4">
                                        <label class="d-block font-weight-bold">Foto Profile</label>
                                        <img src="{{ $item->photos ? Storage::url($item->photos) : 'https://ui-avatars.com/api/?name=' . urlencode($item->name) . '&background=0D8ABC&color=fff' }}" 
                                             class="rounded-circle border mb-3" 
                                             style="height: 120px; width: 120px; object-fit: cover;">
                                        <input type="file" name="photos" class="form-control mx-auto w-50" accept="image/*" />
                                        <small class="text-muted d-block mt-2 font-italic">Biarkan kosong jika tidak ingin ganti foto</small>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group"><label>Nama User</label>
                                            <input type="text" class="form-control" name="name" value="{{ $item->name }}" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group"><label>Email User</label>
                                            <input type="email" class="form-control" name="email" value="{{ $item->email }}" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group"><label>Password User</label>
                                            <input type="password" class="form-control" name="password" />
                                            <small class="text-info font-italic">Kosongkan jika tidak ganti password</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group"><label>Roles</label>
                                            <select name="roles" required class="form-control">
                                                <option value="ADMIN" {{ $item->roles == 'ADMIN' ? 'selected' : '' }}>ADMIN</option>
                                                <option value="USER" {{ $item->roles == 'USER' ? 'selected' : '' }}>USER</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col text-right">
                                        <button type="submit" class="btn btn-success px-5">Update User</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection