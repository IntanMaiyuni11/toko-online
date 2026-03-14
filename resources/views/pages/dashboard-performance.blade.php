@extends('layouts.dashboard')

@section('title', 'Performance')

@section('content')
<div class="section-content section-dashboard-home" data-aos="fade-up">
    <div class="container-fluid">
        <div class="dashboard-heading">
            <h2 class="dashboard-title">Performa Toko</h2>
            <p class="dashboard-subtitle">Rating dan kepuasan pembeli berdasarkan ulasan asli</p>
        </div>
        
        <div class="dashboard-content">
            {{-- BARIS KARTU STATISTIK --}}
            <div class="row">
                {{-- RATING KUALITAS PRODUK --}}
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm h-100 p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted mb-2">Kualitas Produk</h6>
                                <h2 class="text-warning font-weight-bold mb-1">
                                    {{ number_format($averageRating ?? 0, 1) }} <span class="text-muted small" style="font-size: 14px">/ 5.0</span>
                                </h2>
                            </div>
                            <div class="icon-circle bg-light-warning text-warning p-3 rounded-circle">
                                <i class="fa fa-star fa-lg"></i>
                            </div>
                        </div>
                        <p class="small text-muted mt-2 mb-0">Rata-rata rating bintang dari pembeli</p>
                    </div>
                </div>

                {{-- TINGKAT PESANAN SUKSES --}}
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm h-100 p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted mb-2">Pesanan Sukses</h6>
                                <h2 class="text-primary font-weight-bold mb-1">
                                    {{ $performanceRate }}%
                                </h2>
                            </div>
                            <div class="icon-circle bg-light-primary text-primary p-3 rounded-circle">
                                <i class="fa fa-shopping-cart fa-lg"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $performanceRate }}%" aria-valuenow="{{ $performanceRate }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p class="small text-muted mt-2 mb-0">Tingkat keberhasilan pengiriman barang</p>
                    </div>
                </div>

                {{-- TOTAL ULASAN (TAMBAHAN) --}}
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm h-100 p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted mb-2">Total Ulasan</h6>
                                <h2 class="text-success font-weight-bold mb-1">
                                    {{ $totalReviews ?? 0 }}
                                </h2>
                            </div>
                            <div class="icon-circle bg-light-success text-success p-3 rounded-circle">
                                <i class="fa fa-comments fa-lg"></i>
                            </div>
                        </div>
                        <p class="small text-muted mt-2 mb-0">Jumlah feedback yang Anda terima</p>
                    </div>
                </div>
            </div>

            {{-- DAFTAR ULASAN TERBARU --}}
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="font-weight-bold mb-4">Ulasan Terbaru</h5>
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <thead>
                                        <tr class="text-muted small uppercase">
                                            <th>Produk</th>
                                            <th>Pembeli</th>
                                            <th>Rating</th>
                                            <th>Komentar</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($reviews ?? [] as $review)
                                        <tr class="border-bottom">
                                            <td class="py-3">
                                                <strong>{{ $review->product->name }}</strong>
                                            </td>
                                            <td>{{ $review->user->name }}</td>
                                            <td>
                                                <span class="text-warning">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star"></i>
                                                    @endfor
                                                </span>
                                            </td>
                                            <td class="text-muted italic small">"{{ $review->comment }}"</td>
                                            <td>{{ $review->created_at->format('d M Y') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted">
                                                Belum ada ulasan untuk ditampilkan.
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
    .bg-light-warning { background-color: #fff8e1; }
    .bg-light-primary { background-color: #e3f2fd; }
    .bg-light-success { background-color: #e8f5e9; }
    .progress { border-radius: 10px; }
    .table thead th { border: none; }
    .italic { font-style: italic; }
</style>
@endpush