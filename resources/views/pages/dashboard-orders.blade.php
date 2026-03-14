@extends('layouts.dashboard')

@section('title', 'Daftar Pesanan')

@section('content')
{{-- Tambahkan padding top (pt-5) untuk menjauhkan dari navbar atas --}}
<div class="section-content section-dashboard-home pt-5" data-aos="fade-up">
    <div class="container-fluid">
        {{-- Gunakan row untuk memisahkan Judul dan Aksi agar lebih terkontrol --}}
        <div class="dashboard-heading mb-4">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <h2 class="dashboard-title">Daftar Pesanan</h2>
                    <p class="dashboard-subtitle">Kelola pesanan pelanggan Anda secara efisien</p>
                </div>
                <div class="col-md-5 text-md-right">
                    <div class="dashboard-action">
                        {{-- Tambahkan margin-right yang cukup agar tidak menempel ke foto profil --}}
                        <button class="btn btn-outline-secondary btn-sm px-3 mr-md-5">
                            <i class="fas fa-download mr-1"></i> Download Laporan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-content">
            {{-- Navigasi Tabs Utama --}}
            <ul class="nav nav-tabs order-tabs border-bottom" id="orderTab" role="tablist">
                @php
                    $tabs = [
                        'ALL' => 'Semua Pesanan',
                        'PENDING' => 'Pesanan Baru',
                        'SHIPPING' => 'Siap Dikirim',
                        'SUCCESS' => 'Pesanan Selesai',
                        'CANCELLED' => 'Dibatalkan'
                    ];
                @endphp
                @foreach($tabs as $key => $label)
                <li class="nav-item">
                    <a class="nav-link {{ $activeStatus == $key ? 'active' : '' }}" 
                       href="{{ route('dashboard-orders', ['status' => $key]) }}">
                       {{ $label }}
                    </a>
                </li>
                @endforeach
            </ul>

          {{-- Filter Bar --}}
<form action="{{ route('dashboard-orders') }}" method="GET" id="filterForm">
    {{-- Hidden input agar filter status tetap terjaga --}}
    <input type="hidden" name="status" value="{{ $activeStatus }}">

    <div class="row mt-3 mb-4">
        <div class="col-md-12 d-flex align-items-center flex-wrap">
            {{-- Search Box --}}
            <div class="input-group shadow-sm mr-2 mb-2" style="max-width: 300px;">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white border-right-0"><i class="fa fa-search text-muted"></i></span>
                </div>
                <input type="text" name="keyword" value="{{ request('keyword') }}" 
                       class="form-control border-left-0" placeholder="Cari No. Invoice...">
            </div>

            {{-- Filter Kalender --}}
            <div class="input-group shadow-sm mr-2 mb-2" style="max-width: 300px;">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white border-right-0"><i class="fa fa-calendar text-muted"></i></span>
                </div>
                <input type="text" id="dateRange" name="date_range" 
                       class="form-control border-left-0 bg-white" 
                       placeholder="Pilih Rentang Tanggal" 
                       value="{{ request('date_range') }}" readonly>
            </div>

            {{-- Tombol Aksi --}}
            <button type="submit" class="btn btn-success btn-sm font-weight-bold mb-2 px-4 shadow-sm">
                Filter Sekarang
            </button>
            <a href="{{ route('dashboard-orders', ['status' => $activeStatus]) }}" 
               class="btn btn-link btn-sm text-muted mb-2 ml-2">
               Reset Filter
            </a>
        </div>
    </div>
</form>
            {{-- Loop Daftar Pesanan --}}
            <div class="order-list">
                @forelse ($order_groups as $transactions_id => $details)
                @php
                    // Ambil data transaksi utama dari item pertama dalam grup
                    $firstItem = $details->first();
                    $mainTransaction = $firstItem->transaction;
                @endphp
                <div class="card mb-4 border rounded-lg shadow-sm">
                    {{-- Header Pesanan --}}
                    <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                        <div class="d-flex justify-content-between">
                            <div>
                                <span class="badge badge-status {{ strtolower($firstItem->shipping_status) }}">
                                    Pesanan {{ $firstItem->shipping_status }}
                                </span>
                                <span class="text-success font-weight-bold ml-2">INV/{{ $mainTransaction->code }}</span>
                                <span class="text-muted small ml-2">/ {{ $mainTransaction->user->name }} / {{ $firstItem->created_at->format('d M Y, H:i') }} WIB</span>
                            </div>
                        </div>
                    </div>

                    {{-- Body Pesanan --}}
                    <div class="card-body pt-2">
                        <div class="row">
                            {{-- Info Produk (Looping semua produk dalam 1 transaksi ini) --}}
                            <div class="col-md-4 border-right">
                                @foreach ($details as $detail)
                                <div class="media mb-2">
                                    <img src="{{ Storage::url($detail->product->galleries->first()->photos ?? 'assets/images/no-image.jpg') }}" 
                                        class="mr-3 rounded border" style="width: 50px; height: 50px; object-fit: cover;">
                                    <div class="media-body">
                                        <h6 class="mt-0 font-weight-bold mb-0 small">{{ $detail->product->name }}</h6>
                                        <p class="small mb-0">{{ $detail->quantity ?? 1 }}x Rp {{ number_format($detail->price) }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            {{-- Info Alamat --}}
                            <div class="col-md-3 border-right">
                                <p class="small font-weight-bold text-muted mb-1">Alamat Pengiriman</p>
                                <p class="small mb-0 font-weight-bold">{{ $mainTransaction->user->name }}</p>
                                <p class="small text-muted mb-0">{{ $mainTransaction->user->address_one }}</p>
                                <p class="small text-muted mb-0">{{ $mainTransaction->user->phone_number }}</p>
                            </div>

                            {{-- Info Kurir --}}
                            <div class="col-md-3">
                                <p class="small font-weight-bold text-muted mb-1">Resi / Kurir</p>
                                <p class="small font-weight-bold mb-0 text-uppercase text-success">{{ $firstItem->resi ?? 'BELUM INPUT RESI' }}</p>
                                <p class="small text-muted mb-0">Status: {{ $firstItem->shipping_status }}</p>
                            </div>

                            {{-- Total Harga --}}
                            <div class="col-md-2 text-right d-flex flex-column justify-content-center">
                                <p class="small text-muted mb-0">Total Bayar</p>
                                <h5 class="font-weight-bold text-success">Rp{{ number_format($mainTransaction->total_price) }}</h5>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Action --}}
                    <div class="card-footer bg-light border-top-0 d-flex justify-content-between align-items-center py-2">
                       <div>
        {{-- Kita gunakan ID dari $firstItem --}}
                        <a href="{{ route('dashboard-transaction-details', $firstItem->id) }}" class="btn btn-link text-dark btn-sm">
                            <i class="fas fa-list-alt mr-1"></i> Detail Pesanan
                        </a>
                    </div>
                    <div class="d-flex align-items-center">
                        <a href="{{ route('dashboard-transaction-details', $firstItem->id) }}" class="btn btn-success btn-sm px-4">
                            Kelola Pesanan
                        </a>
                    </div>
                </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <p class="text-muted">Tidak ada pesanan masuk.</p>
                </div>
                @endforelse

                {{-- Catatan: Paginasi dimatikan sementara karena menggunakan groupBy get() --}}
</div>
            </div>
            </div>
        </div>
    </div>
@endsection

@push('addon-script')
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const picker = new Litepicker({
            element: document.getElementById('dateRange'),
            singleMode: false,
            numberOfMonths: 2,
            numberOfColumns: 2,
            format: 'DD MMM YYYY',
            allowRepick: true,
            dropdowns: {
                minYear: 2020,
                maxYear: null,
                months: true,
                years: true
            },
            // Sinkronisasi dengan tombol Filter
            setup: (picker) => {
                picker.on('selected', (date1, date2) => {
                    // Jika ingin otomatis submit saat tanggal dipilih, aktifkan baris di bawah ini:
                    // document.getElementById('filterForm').submit();
                });
            },
        });
    });
</script>
@endpush
@push('addon-style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css">
<style>
    .litepicker .container__days .day-item.is-start-date,
    .litepicker .container__days .day-item.is-end-date {
        background-color: #28a745 !important;
    }
    .litepicker .container__days .day-item.is-in-range {
        background-color: #e6f6ec !important;
        color: #28a745 !important;
    }
</style>
<style>
    .pagination .page-item.active .page-link {
        background-color: #28a745 !important;
        border-color: #28a745 !important;
        color: white !important;
    }
    .pagination .page-link {
        color: #28a745;
    }
</style>
@endpush