@extends('layouts.admin')

@section('title')
    Admin Reward
@endsection

@section('content')
<div class="section-content section-dashboard-home" data-aos="fade-up">
    <div class="container-fluid">
        <div class="dashboard-heading">
            <h2 class="dashboard-title">Reward</h2>
            <p class="dashboard-subtitle">Manage your rewards & discounts</p>
        </div>
        <div class="dashboard-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ route('admin.rewards.create') }}" class="btn btn-primary mb-3">
                                + Tambah Reward Baru
                            </a>
                            <div class="table-responsive">
                                <table class="table table-hover scroll-horizontal-vertical w-100" id="crudTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nama Reward</th>
                                            <th>Poin Dibutuhkan</th>
                                            <th>Tipe Diskon</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        </tbody>
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
            ajax: {
                url: '{!! url()->current() !!}',
            },
            columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'points', name: 'points' },
            // Pastikan ini discount_amount jika di HTML tertulis "Nominal/Tipe Diskon"
            { data: 'discount_amount', name: 'discount_amount' }, 
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                width: '15%'
            },
]
        });
    </script>
@endpush