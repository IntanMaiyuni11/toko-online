@extends('layouts.dashboard')

@section('title')
    Store Dashboard
@endsection

@section('content')
<div class="section-content section-dashboard-home" data-aos="fade-up">
    <div class="container-fluid">
        <div class="dashboard-heading mt-10">
            <h2 class="dashboard-title">
                {{ Auth::user()->roles == 'CUSTOMER' ? 'Halo, ' . Auth::user()->name . '! 👋' : 'Seller Dashboard' }}
            </h2>
            <p class="dashboard-subtitle">
                {{ Auth::user()->roles == 'CUSTOMER' 
                    ? 'Senang melihat Anda kembali. Yuk, cek status pesanan atau lanjut belanja!' 
                    : 'Monitor performa toko Anda hari ini' }}
            </p>
        </div>
        
        <div class="dashboard-content">
            @if(Auth::user()->roles == 'CUSTOMER')
                {{-- TAMPILAN KHUSUS CUSTOMER (LEBIH VISUAL) --}}
                <div class="row">
                    {{-- WIDGET STATUS PESANAN DENGAN IKON --}}
                    <div class="col-md-3">
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-shape bg-light-primary text-primary rounded-circle mr-3 p-3">
                                        <i class="fa fa-shopping-bag fa-lg"></i>
                                    </div>
                                    <div>
                                        <div class="small text-muted font-weight-bold">Total Pesanan</div>
                                        <div class="h5 mb-0 font-weight-bold">{{ number_format($transaction_count ?? 0) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-body p-3 text-decoration-none">
                                <div class="d-flex align-items-center">
                                    <div class="icon-shape bg-light-warning text-warning rounded-circle mr-3 p-3">
                                        <i class="fa fa-clock fa-lg"></i>
                                    </div>
                                    <div>
                                        <div class="small text-muted font-weight-bold">Menunggu</div>
                                        <div class="h5 mb-0 font-weight-bold">{{ number_format($pending_count ?? 0) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-shape bg-light-info text-info rounded-circle mr-3 p-3">
                                        <i class="fa fa-truck fa-lg"></i>
                                    </div>
                                    <div>
                                        <div class="small text-muted font-weight-bold">Dikirim</div>
                                        <div class="h5 mb-0 font-weight-bold">{{ number_format($shipping_count ?? 0) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-shape bg-light-success text-success rounded-circle mr-3 p-3">
                                        <i class="fa fa-check-circle fa-lg"></i>
                                    </div>
                                    <div>
                                        <div class="small text-muted font-weight-bold">Selesai</div>
                                        <div class="h5 mb-0 font-weight-bold">{{ number_format($completed_count ?? 0) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    {{-- BANNER PROMO / AJAKAN BELANJA --}}
                    <div class="col-md-8">
                        <div class="card bg-primary text-white border-0 shadow-sm mb-4" style="border-radius: 15px; background: linear-gradient(45deg, #2980b9, #6dd5fa);">
                            <div class="card-body p-4 d-flex align-items-center">
                                <div>
                                    <h4 class="font-weight-bold">Mau belanja apa hari ini?</h4>
                                    <p class="mb-3">Temukan produk impianmu dengan harga terbaik hanya di Store kami.</p>
                                    <a href="{{ route('home') }}" class="btn btn-light btn-sm font-weight-bold px-4 py-2" style="border-radius: 8px;">Mulai Belanja</a>
                                </div>
                                <img src="{{ url('/images/shopping-illustration.jpg') }}" class="d-none d-lg-block ml-auto" style="height: 120px;">
                            </div>
                        </div>
                    </div>
                    {{-- AKSI CEPAT CUSTOMER --}}
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <h6 class="font-weight-bold mb-3">Aksi Cepat</h6>
                                <div class="list-group list-group-flush">
                                    <a href="{{ route('dashboard-settings-account') }}" class="list-group-item list-group-item-action border-0 px-0">
                                        <i class="fa fa-user-circle text-muted mr-2"></i> Pengaturan Akun
                                    </a>
                                    <a href="{{ route('dashboard-my-orders') }}" class="list-group-item list-group-item-action border-0 px-0">
                                        <i class="fa fa-list-alt text-muted mr-2"></i> Riwayat Pesanan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                {{-- TAMPILAN STATS SELLER (Dibiarkan tetap seperti sebelumnya) --}}
                <div class="row">
                    <div class="col-md-4">
                        <div class="card mb-2 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="dashboard-card-title">Customer</div>
                                <div class="dashboard-card-subtitle">{{ number_format($customer ?? 0) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-2 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="dashboard-card-title">Revenue</div>
                                <div class="dashboard-card-subtitle">Rp {{ number_format($revenue ?? 0) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-2 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="dashboard-card-title">Transaction</div>
                                <div class="dashboard-card-subtitle">{{ number_format($transaction_count ?? 0) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SELLER CHART & PENTING HARI INI --}}
                <div class="row mt-4">
                    <div class="col-md-8">
                        <h5 class="mb-3 font-weight-bold">Penting hari ini</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card mb-3 shadow-sm border-0 text-center">
                                    <div class="card-body">
                                        <div class="small text-muted mb-1">Pesanan baru</div>
                                        <h3 class="font-weight-bold mb-0 text-primary">{{ $new_orders ?? 0 }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card mb-3 shadow-sm border-0 text-center">
                                    <div class="card-body">
                                        <div class="small text-muted mb-1">Siap dikirim</div>
                                        <h3 class="font-weight-bold mb-0 text-warning">{{ $ready_to_ship ?? 0 }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card mb-3 shadow-sm border-0 text-center">
                                    <div class="card-body">
                                        <div class="small text-muted mb-1">Ulasan baru</div>
                                        <h3 class="font-weight-bold mb-0 text-success">{{ $new_reviews ?? 0 }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-4 shadow-sm border-0">
                            <div class="card-body">
                                <h5 class="font-weight-bold mb-3">Tren Penjualan (7 Hari Terakhir)</h5>
                                <canvas id="sellerChart" height="120"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card mb-3 shadow-sm border-0">
                            <div class="card-body">
                                <h5 class="font-weight-bold mb-3">Aksi Cepat</h5>
                                <div class="list-group list-group-flush">
                                    <a href="{{ route('dashboard-product-create') }}" class="list-group-item list-group-item-action border-0 px-0">
                                        <i class="fa fa-plus-circle text-primary mr-2"></i> Tambah Produk Baru
                                    </a>
                                    <a href="{{ route('dashboard-orders') }}" class="list-group-item list-group-item-action border-0 px-0">
                                        <i class="fa fa-truck text-warning mr-2"></i> Cek Pesanan Masuk
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- DAFTAR TRANSAKSI TERBARU (SAMA UNTUK SEMUA) --}}
            <div class="row mt-4">
                <div class="col-12 mt-2">
                    <h5 class="mb-3 font-weight-bold">Transaksi Terakhir</h5>
                    @forelse ($transaction_data as $transaction)
                        <a class="card card-list d-block mb-2 text-decoration-none text-dark shadow-sm border-0" href="{{ route('dashboard-transaction-details', $transaction->id) }}" style="border-radius: 10px;">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-1">
                                        <img src="{{ Storage::url($transaction->product->galleries->first()->photos ?? 'assets/default.jpg') }}" class="w-100 rounded" />
                                    </div>
                                    <div class="col-md-4 font-weight-bold">
                                        {{ $transaction->product->name }}
                                    </div>
                                    <div class="col-md-3 text-muted">
                                        {{ Auth::user()->roles == 'CUSTOMER' ? 'Penjual: ' . $transaction->product->user->store_name : $transaction->transaction->user->name }}
                                    </div>
                                    <div class="col-md-2 text-muted">
                                        {{ $transaction->created_at->format('d M, Y') }}
                                    </div>
                                    <div class="col-md-2 d-none d-md-block">
                                        @php
                                            $badgeClass = [
                                                'PENDING' => 'badge-warning',
                                                'SHIPPING' => 'badge-info',
                                                'SUCCESS' => 'badge-success'
                                            ][$transaction->shipping_status] ?? 'badge-secondary';
                                        @endphp
                                        <span class="badge {{ $badgeClass }} px-3 py-2">
                                            {{ $transaction->shipping_status }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="card card-body text-center py-5 shadow-sm border-0" style="border-radius: 15px;">
                            <p class="text-muted mb-0">Belum ada transaksi terbaru.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Tambahkan CSS Tambahan --}}
@push('addon-style')
<style>
    .bg-light-primary { background-color: #e3f2fd; }
    .bg-light-warning { background-color: #fff3e0; }
    .bg-light-info { background-color: #e0f7fa; }
    .bg-light-success { background-color: #e8f5e9; }
    .icon-shape {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .card-list:hover {
        transform: translateY(-3px);
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
    }
</style>
@endpush

@push('addon-script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var chartElement = document.getElementById('sellerChart');
    if (chartElement) {
        var ctx = chartElement.getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chart_labels ?? []) !!}, 
                datasets: [{
                    label: 'Produk Terjual',
                    data: {!! json_encode($chart_data ?? []) !!},
                    backgroundColor: 'rgba(56, 193, 114, 0.1)',
                    borderColor: '#38c172',
                    borderWidth: 3,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
                plugins: { legend: { display: false } }
            }
        });
    }
</script>
@endpush