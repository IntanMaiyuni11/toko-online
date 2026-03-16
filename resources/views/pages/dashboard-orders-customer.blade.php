@extends('layouts.dashboard')

@section('title', 'Pesanan Saya')

@section('content')
<div class="section-content section-dashboard-home" data-aos="fade-up">
    <div class="container-fluid">
        <div class="dashboard-heading mb-4">
            <h2 class="dashboard-title">Daftar Transaksi</h2>
            <p class="dashboard-subtitle">Pantau status belanjaan kamu di sini</p>
        </div>

        <div class="dashboard-content">
            {{-- Navigasi Tabs --}}
            <ul class="nav nav-tabs order-tabs border-bottom mb-4" id="orderTab" role="tablist">
                @php
                    $tabs = [
                        'ALL' => 'Semua', 
                        'PENDING' => 'Menunggu', 
                        'SHIPPING' => 'Siap Dikirim',  
                        'SUCCESS' => 'Selesai', 
                        'CANCELLED' => 'Dibatalkan'
                    ];
                @endphp
                @foreach($tabs as $key => $label)
                <li class="nav-item">
                    <a class="nav-link {{ $activeStatus == $key ? 'active' : '' }} font-weight-bold" 
                       href="{{ route('dashboard-my-orders', ['status' => $key]) }}">
                       {{ $label }}
                    </a>
                </li>
                @endforeach
            </ul>

            {{-- Filter Bar --}}
            <form action="{{ route('dashboard-my-orders') }}" method="GET">
                <input type="hidden" name="status" value="{{ $activeStatus }}">
                <div class="row mb-4 align-items-end">
                    <div class="col-lg-5 col-md-12 mb-3 mb-lg-0">
                        <div class="input-group search-box shadow-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white border-right-0"><i class="fa fa-search text-muted"></i></span>
                            </div>
                            <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control border-left-0" placeholder="Cari No. Invoice atau Nama Produk">
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-8 mb-3 mb-lg-0">
                        <div class="input-group shadow-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white border-right-0"><i class="fa fa-calendar-alt text-muted"></i></span>
                            </div>
                            <input type="text" id="dateRange" name="date_range" value="{{ $selectedDate ?? '' }}" 
                                   class="form-control border-left-0 bg-white" placeholder="Pilih Rentang Tanggal" readonly>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 text-right">
                        <button type="submit" class="btn btn-success font-weight-bold shadow-sm py-2 px-4">Cari</button>
                        <a href="{{ route('dashboard-my-orders') }}" class="btn btn-outline-secondary font-weight-bold shadow-sm py-2 px-3">Reset</a>
                    </div>
                </div>
            </form>

            {{-- List Order --}}
            <div class="tab-content">
                {{-- PERBAIKAN: Loop pertama mengambil grup per transaksi --}}
                @forelse ($order_groups as $transactionId => $details)
                <div class="card mb-3 shadow-sm border-0" style="border-radius: 12px; border: 1px solid #eee !important;">
                    <div class="card-body p-4">
                        {{-- Header Transaksi (Muncul 1x per kotak) --}}
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-shopping-bag text-success mr-2"></i>
                            <span class="font-weight-bold mr-2" style="font-size: 14px;">Belanja</span>
                            <span class="text-muted small mr-3">{{ $details->first()->created_at->format('d M Y') }}</span>
                            <span class="badge py-1 px-2 mr-3" style="background-color: #fef3c7; color: #92400e; border-radius: 4px; font-size: 11px;">
                                {{ str_replace('_', ' ', $details->first()->shipping_status) }}
                            </span>
                            <span class="text-muted small font-weight-light">{{ $details->first()->transaction->code }}</span>
                        </div>

                        {{-- Nama Toko (Muncul 1x per kotak) --}}
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 18px; height: 18px; background-color: #7249db;">
                                <i class="fas fa-check text-white" style="font-size: 10px;"></i>
                            </div>
                            <span class="font-weight-bold text-dark" style="font-size: 15px;">{{ $details->first()->product->user->store_name ?? 'Toko Tidak Diketahui' }}</span>
                        </div>

                        {{-- PERBAIKAN: Loop kedua mengambil semua produk dalam transaksi ini --}}
                        @php $totalPrice = 0; @endphp
                        @foreach ($details as $order)
                        @php $totalPrice += $order->price; @endphp
                        <div class="row align-items-start {{ !$loop->last ? 'mb-4' : '' }}">
                            <div class="col-md-1 col-3">
                                <img src="{{ $order->product->galleries->first() ? Storage::url($order->product->galleries->first()->photos) : '/images/default-product.jpg' }}" 
                                     class="w-100 rounded" style="aspect-ratio: 1/1; object-fit: cover;" />
                            </div>
                            <div class="col-md-11 col-9">
                                <div class="font-weight-bold text-dark mb-1" style="font-size: 16px;">{{ strtoupper($order->product->name) }}</div>
                                <div class="text-muted small">1 barang x Rp {{ number_format($order->price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        @endforeach

                        {{-- Info Total & Tombol (Muncul di bawah daftar produk) --}}
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-4 pt-3 border-top" style="gap: 12px;">
                            <div>
                                <div class="text-muted small">Total Belanja</div>
                                <div class="font-weight-bold text-dark" style="font-size: 18px;">Rp {{ number_format($totalPrice, 0, ',', '.') }}</div>
                            </div>
                            
                            <div class="d-flex align-items-center mt-3 mt-md-0" style="gap: 12px;">
                                <a href="javascript:void(0)" class="text-success font-weight-bold small mr-3" data-toggle="modal" data-target="#detailModal{{ $details->first()->id }}">
                                    Lihat Detail Transaksi
                                </a>

                                <a href="{{ route('dashboard-chat', ['user_id' => $details->first()->product->users_id, 'product_id' => $details->first()->product->id]) }}" 
                                class="btn btn-outline-primary font-weight-bold px-4 py-2" 
                                style="border-radius: 8px; font-size: 13px;">
                                    <i class="fas fa-comments mr-1"></i> Chat Penjual
                                </a>

                                <button class="btn btn-outline-success font-weight-bold px-4 py-2" 
                                        style="border-radius: 8px; font-size: 13px;" 
                                        data-toggle="modal" 
                                        data-target="#lacakModal{{ $details->first()->id }}">
                                    Lacak
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <p class="text-muted">Belum ada transaksi di status ini</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Modal include menggunakan grup --}}
@foreach ($order_groups as $details)
    @php $order = $details->first(); @endphp
    @include('pages.modals.order-detail', ['order' => $order])
    @include('pages.modals.order-lacak', ['order' => $order])
@endforeach

@endsection

@push('addon-script')
<script>
    $(document).ready(function() {
        // Solusi utama: Pindahkan modal ke body agar tidak terhalang backdrop CSS
        $('.modal').appendTo("body");

        // Memastikan urutan tumpukan (z-index) benar saat modal muncul
        $('.modal').on('show.bs.modal', function () {
            $(this).css('z-index', 1060);
            setTimeout(function() {
                $('.modal-backdrop').not('.modal-stack').css('z-index', 1059).addClass('modal-stack');
            }, 0);
        });
    });
</script>
@endpush