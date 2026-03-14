@extends('layouts.admin')

@section('title', 'Admin - Create User')

@section('content')
<div class="section-content section-dashboard-home" data-aos="fade-up">
    <div class="container-fluid">
        <div class="dashboard-heading">
            <h2 class="dashboard-title">User</h2>
            <p class="dashboard-subtitle">Create New User</p>
        </div>
        <div class="dashboard-content">
            <div class="row">
                <div class="col-12">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                        </div>
                    @endif
                    <form action="{{ route('admin.user.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group text-center">
                                            <label class="d-block font-weight-bold">Foto Profile</label>
                                            <input type="file" name="photos" class="form-control mx-auto w-50" accept="image/*" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group"><label>Nama User</label>
                                            <input type="text" class="form-control" name="name" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group"><label>Email User</label>
                                            <input type="email" class="form-control" name="email" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group"><label>Password User</label>
                                            <input type="password" class="form-control" name="password" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group"><label>Roles</label>
                                            <select name="roles" required class="form-control">
                                                <option value="ADMIN">ADMIN</option>
                                                <option value="USER">USER</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col text-right">
                                        <button type="submit" class="btn btn-success px-5">Save Now</button>
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