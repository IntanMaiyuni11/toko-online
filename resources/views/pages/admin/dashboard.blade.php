@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="section-content section-dashboard-home" data-aos="fade-up">
    <div class="container-fluid">
        <div class="dashboard-heading">
            <h2 class="dashboard-title">Admin Dashboard</h2>
            <p class="dashboard-subtitle">Administrator Panel & Global Overview</p>
        </div>

        <div class="dashboard-content">
            {{-- STATISTIK UTAMA --}}
            <div class="row">
                <div class="col-md-4">
                    <div class="card mb-4 border-0 shadow-sm overflow-hidden">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1 small uppercase font-weight-bold">Total Pengguna</p>
                                    <h3 class="font-weight-bold mb-0">{{ number_format($customer) }}</h3>
                                </div>
                                <div class="icon-box bg-soft-primary text-primary rounded-circle">
                                    <i class="fa fa-users fa-lg"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 70%"></div>
                                </div>
                                <p class="text-muted small mt-2 mb-0">+5% dibanding bulan lalu</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card mb-4 border-0 shadow-sm overflow-hidden">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1 small uppercase font-weight-bold">Total Pendapatan</p>
                                    <h3 class="font-weight-bold mb-0">Rp {{ number_format($revenue, 0, ',', '.') }}</h3>
                                </div>
                                <div class="icon-box bg-soft-success text-success rounded-circle">
                                    <i class="fa fa-wallet fa-lg"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 85%"></div>
                                </div>
                                <p class="text-muted small mt-2 mb-0">85% dari target kuartal</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card mb-4 border-0 shadow-sm overflow-hidden">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1 small uppercase font-weight-bold">Total Transaksi</p>
                                    <h3 class="font-weight-bold mb-0">{{ number_format($transaction) }}</h3>
                                </div>
                                <div class="icon-box bg-soft-warning text-warning rounded-circle">
                                    <i class="fa fa-shopping-bag fa-lg"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 60%"></div>
                                </div>
                                <p class="text-muted small mt-2 mb-0">Menunggu 12 konfirmasi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TRANSAKSI TERBARU --}}
            <div class="row mt-2">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="font-weight-bold">Transaksi Global Terbaru</h5>
                    </div>
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="border-0 px-4 py-3">Resi/Kode</th>
                                            <th class="border-0 py-3">Nama Customer</th>
                                            <th class="border-0 py-3 text-center">Total Harga</th>
                                            <th class="border-0 py-3 text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recent_transactions as $t)
                                        <tr>
                                            <td class="px-4 py-3 font-weight-bold text-dark">#{{ $t->code }}</td>
                                            <td class="py-3">{{ $t->user->name }}</td>
                                            <td class="py-3 text-center text-success font-weight-bold">
                                                Rp {{ number_format($t->total_price, 0, ',', '.') }}
                                            </td>
                                            <td class="py-3 text-center">
                                                @if($t->transaction_status == 'SUCCESS')
                                                    <span class="badge badge-pill badge-soft-success px-3">Berhasil</span>
                                                @elseif($t->transaction_status == 'PENDING')
                                                    <span class="badge badge-pill badge-soft-warning px-3">Pending</span>
                                                @else
                                                    <span class="badge badge-pill badge-soft-danger px-3">{{ $t->transaction_status }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted small italic">
                                                Belum ada transaksi terekam di sistem.
                                            </td>
                                        </tr>
                                        @endforelse
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

@push('addon-style')
<style>
    .bg-soft-primary { background-color: #e7f1ff; }
    .bg-soft-success { background-color: #e1f7ed; }
    .bg-soft-warning { background-color: #fff8e1; }
    .badge-soft-success { background-color: #e1f7ed; color: #00b894; }
    .badge-soft-warning { background-color: #fff8e1; color: #f1c40f; }
    .badge-soft-danger { background-color: #ffebee; color: #d63031; }
    
    .icon-box {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .table thead th {
        font-size: 11px;
        letter-spacing: 0.1em;
        color: #8898aa;
    }
</style>
@endpush