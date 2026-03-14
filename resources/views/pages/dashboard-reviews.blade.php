@extends('layouts.dashboard')

@section('title')
  Manajemen Ulasan
@endsection

@section('content')
<div class="section-content section-dashboard-home pt-5">
    <div class="container-fluid">
        <div class="dashboard-heading mb-4">
            <h2 class="dashboard-title">Manajemen Ulasan</h2>
            <p class="dashboard-subtitle">Lihat dan balas ulasan dari pelanggan Anda</p>
        </div>

        {{-- ROLE SELLER / ADMIN --}}
        @if(Auth::user()->roles == 'USER' || Auth::user()->roles == 'ADMIN')
            <ul class="nav nav-tabs border-bottom mb-0" id="reviewTab" role="tablist" style="border-width: 2px !important;">
                <li class="nav-item">
                    <a class="nav-link active font-weight-bold" id="rating-tab" data-toggle="tab" href="#rating-produk" role="tab">Rating Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-muted font-weight-bold" id="inbox-tab" data-toggle="tab" href="#inbox-ulasan" role="tab">Inbox Ulasan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-muted font-weight-bold" id="penilaian-tab" data-toggle="tab" href="#penilaian-pembeli" role="tab">Penilaian Pembeli</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-muted font-weight-bold" id="penalty-tab" data-toggle="tab" href="#penalty-reward" role="tab">Penalty Dan Reward</a>
                </li>
            </ul>

            <div class="tab-content mt-3" id="reviewTabContent">
                {{-- TAB 1: RATING PRODUK (SELLER) --}}
                <div class="tab-pane fade show active" id="rating-produk" role="tabpanel">
                    <div class="card border-0 shadow-sm mb-5">
                        <div class="card-body p-4">
                            <h6 class="text-dark font-weight-bold mb-3">Rata-rata rating {{ $product_stats->count() }} produk</h6>
                            <div class="row align-items-center mb-5">
                                <div class="col-md-5 d-flex align-items-center">
                                    <span class="text-warning mr-2" style="font-size: 2.5rem;">★</span>
                                    <h1 class="display-4 font-weight-bold mb-0 mr-2 text-dark">
                                        {{ number_format($product_stats->avg('avg_rating') ?? 0, 1) }}
                                    </h1>
                                    <span class="text-muted h4 mb-0 mt-2">/ 5.0</span>
                                    <div class="ml-4 border-left pl-4">
                                        <p class="mb-0 font-weight-bold text-dark">{{ $product_stats->sum('total_reviews') }} ulasan</p>
                                        <small class="text-muted">Periode: {{ \Carbon\Carbon::now()->subYear()->translatedFormat('j M Y') }} - Hari ini</small>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-row justify-content-end">
                                        <div class="col-md-3 mt-2">
                                            <select class="form-control form-control-sm custom-select"><option>1 Tahun Terakhir</option></select>
                                        </div>
                                        <div class="col-md-3 mt-2">
                                            <select class="form-control form-control-sm custom-select"><option>Ulasan Terbanyak</option></select>
                                        </div>
                                        <div class="col-md-5 mt-2">
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-prepend"><span class="input-group-text bg-white border-right-0 text-muted"><i class="fas fa-search"></i></span></div>
                                                <input type="text" class="form-control border-left-0" placeholder="Cari nama produk">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover border-top">
                                    <thead class="small text-muted text-uppercase">
                                        <tr>
                                            <th class="border-0 py-3">Nama Produk</th>
                                            <th class="border-0 py-3 text-center">Rating</th>
                                            <th class="border-0 py-3 text-center">Total Ulasan</th>
                                            <th class="border-0 py-3">Topik Populer</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($product_stats as $stat)
                                        <tr class="align-middle">
                                            <td class="py-3">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $stat->galleries->first() ? Storage::url($stat->galleries->first()->photos) : '/images/default-product.jpg' }}" class="rounded border mr-3 shadow-sm" style="width: 48px; height: 48px; object-fit: cover;">
                                                    <span class="small font-weight-bold text-primary">{{ $stat->name }}</span>
                                                </div>
                                            </td>
                                            <td class="text-center py-3"><span class="text-warning mr-1">★</span><strong>{{ number_format($stat->avg_rating, 1) }}</strong></td>
                                            <td class="text-center py-3 text-dark">{{ $stat->total_reviews }} ulasan</td>
                                            <td class="py-3">
                                                @if($stat->avg_rating >= 4.5) <span class="badge badge-light border text-muted px-2 py-1 font-weight-normal" style="font-size: 0.7rem;">Kualitas <span class="ml-1">1</span></span>
                                                @else <span class="text-muted small">Topik tidak tersedia</span> @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="4" class="text-center py-5 text-muted">Belum ada data ulasan produk.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB 2: INBOX ULASAN (SELLER) --}}
                <div class="tab-pane fade" id="inbox-ulasan" role="tabpanel">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            @forelse($reviews as $review)
                                <div class="media p-4 border-bottom hover-bg">
                                    <img src="{{ $review->user->photos ? Storage::url($review->user->photos) : 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name) . '&background=28a745&color=fff' }}" class="mr-3 rounded-circle shadow-sm border" style="width: 50px; height: 50px; object-fit: cover;" />
                                    <div class="media-body">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="font-weight-bold text-dark">{{ $review->user->name }}</h6>
                                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div class="text-warning mb-2" style="font-size: 0.8rem;">
                                            @for($i=1; $i<=5; $i++) <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i> @endfor
                                        </div>
                                        <div class="p-3 bg-light rounded border-left-success text-dark mb-3">"{{ $review->comment }}"</div>
                                        @if($review->seller_reply)
                                            <div class="ml-4 p-3 border-left bg-white rounded shadow-sm reply-box">
                                                <strong class="text-success small d-block mb-1"><i class="fas fa-reply mr-1"></i> Balasan Anda:</strong>
                                                <p class="mb-0 text-muted small italic">{{ $review->seller_reply }}</p>
                                            </div>
                                        @else
                                            <button class="btn btn-sm btn-outline-success font-weight-bold px-4" data-toggle="collapse" data-target="#reply-{{ $review->id }}">Balas</button>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5 text-muted">Belum ada ulasan masuk.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        {{-- ROLE CUSTOMER --}}
        @elseif(Auth::user()->roles == 'CUSTOMER')
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card mb-2 shadow-sm border-0">
                        <div class="card-body">
                            <div class="text-muted small text-uppercase font-weight-bold">Menunggu Diulas</div>
                            <div class="h2 font-weight-bold text-success">{{ $pending_reviews->count() }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-2 shadow-sm border-0">
                        <div class="card-body">
                            <div class="text-muted small text-uppercase font-weight-bold">Total Ulasan Saya</div>
                            <div class="h2 font-weight-bold text-primary">{{ $my_reviews->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <ul class="nav nav-tabs border-bottom mb-4" id="customerReviewTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active font-weight-bold text-success" data-toggle="tab" href="#pending" role="tab">Belum Diulas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold text-muted" data-toggle="tab" href="#history" role="tab">Riwayat Ulasan</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="customerReviewTabContent">
                        {{-- TAB 1: BELUM DIULAS --}}
                        <div class="tab-pane fade show active" id="pending" role="tabpanel">
                            @forelse($pending_reviews as $pending)
                                <div class="card mb-3 border shadow-sm">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $pending->product->galleries->first() ? Storage::url($pending->product->galleries->first()->photos) : '/images/default-product.jpg' }}" class="rounded mr-3" style="width: 60px; height: 60px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-1 font-weight-bold text-dark">{{ $pending->product->name }}</h6>
                                                <small class="text-muted">Pesanan Selesai: {{ $pending->updated_at->format('d M Y') }}</small>
                                            </div>
                                        </div>
                                        <button class="btn btn-success px-4 shadow-sm font-weight-bold btn-review" 
                                                type="button"
                                                data-toggle="modal" 
                                                data-target="#reviewModal{{ $pending->id }}">
                                            Beri Ulasan
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5 text-muted">Semua produk sudah Anda ulas. Terima kasih!</div>
                            @endforelse
                        </div>

                        {{-- TAB 2: RIWAYAT --}}
                        <div class="tab-pane fade" id="history" role="tabpanel">
                            @forelse($my_reviews as $review)
                                <div class="card mb-3 border-light shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <img src="{{ $review->product->galleries->first() ? Storage::url($review->product->galleries->first()->photos) : '/images/default-product.jpg' }}" class="rounded mr-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-0 font-weight-bold text-dark">{{ $review->product->name }}</h6>
                                                <div class="text-warning small">
                                                    @for($i=1; $i<=5; $i++) <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i> @endfor
                                                    <span class="text-muted ml-2 small">{{ $review->created_at->format('d M Y') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-3 bg-light rounded text-dark small border-left">"{{ $review->comment }}"</div>
                                        @if($review->seller_reply)
                                            <div class="mt-3 ml-4 p-2 bg-white border-left border-success rounded shadow-sm">
                                                <small class="text-success font-weight-bold d-block">Balasan Penjual:</small>
                                                <small class="text-muted italic">"{{ $review->seller_reply }}"</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5 text-muted">Anda belum memiliki riwayat ulasan.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- MODAL SECTION --}}
@if(Auth::user()->roles == 'CUSTOMER')
    @foreach($pending_reviews as $pending)
    <div class="modal fade" id="reviewModal{{ $pending->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 bg-light">
                    <h5 class="modal-title font-weight-bold">Beri Ulasan Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('dashboard-reviews-store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="products_id" value="{{ $pending->products_id }}">
                    <div class="modal-body p-4">
                        <div class="d-flex align-items-center mb-4 p-2 border rounded bg-white">
                             <img src="{{ $pending->product->galleries->first() ? Storage::url($pending->product->galleries->first()->photos) : '/images/default-product.jpg' }}" 
                                  class="rounded mr-3 shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                             <div>
                                <small class="text-muted d-block">Nama Produk</small>
                                <span class="text-dark font-weight-bold">{{ $pending->product->name }}</span>
                             </div>
                        </div>
                        <div class="form-group mb-4">
                            <label class="small font-weight-bold text-uppercase text-success">Kualitas Produk</label>
                            <select name="rating" class="form-control custom-select" required>
                                <option value="5">⭐⭐⭐⭐⭐ (Sangat Bagus)</option>
                                <option value="4">⭐⭐⭐⭐ (Bagus)</option>
                                <option value="3">⭐⭐⭐ (Cukup)</option>
                                <option value="2">⭐⭐ (Kurang)</option>
                                <option value="1">⭐ (Buruk)</option>
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <label class="small font-weight-bold text-uppercase text-success">Tulis Ulasan</label>
                            <textarea name="comment" class="form-control" rows="4" placeholder="Bagikan pengalamanmu..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-3 bg-light">
                        <button type="button" class="btn btn-link text-muted" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success px-5 font-weight-bold shadow-sm">Kirim Ulasan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
@endif
@endsection

@push('addon-style')
<style>
    .nav-tabs .nav-link { border: none; padding: 12px 20px; font-size: 0.9rem; color: #6c757d; transition: all 0.3s; }
    .nav-tabs .nav-link.active { border-bottom: 3px solid #28a745 !important; color: #28a745 !important; background: transparent; }
    .table thead th { background-color: #ffffff; border-bottom: 1px solid #dee2e6 !important; color: #888; }
    .border-left-success { border-left: 4px solid #28a745 !important; }
    .reply-box { border-left: 4px solid #28a745 !important; background-color: #f8fff9 !important; }
    .modal-content { border-radius: 12px; }
    
    /* Perbaikan Modal Backdrop agar tidak mengunci layar */
    .modal-backdrop { z-index: 1040 !important; }
    .modal { z-index: 1050 !important; }
</style>
@endpush

@push('addon-script')
<script>
    $(document).ready(function() {
        // Pindahkan semua modal ke body SEGERA setelah halaman load
        // Ini lebih efisien daripada memindahkan satu-satu saat klik
        $('.modal').appendTo("body");

        // Fokus otomatis ke textarea saat modal terbuka
        $('.modal').on('shown.bs.modal', function() {
            $(this).find('textarea').focus();
        });
    });
</script>
@endpush