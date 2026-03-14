@extends('layouts.dashboard')

@section('title', 'Pusat Edukasi Seller')

@section('content')
<div class="section-content section-dashboard-home" data-aos="fade-up">
    <div class="container-fluid">
        <div class="dashboard-heading">
            <h2 class="dashboard-title">Pusat Edukasi Penjual</h2>
            <p class="dashboard-subtitle">Pelajari cara sukses berjualan di platform kami</p>
        </div>
        
        <div class="dashboard-content mt-4">
            {{-- Search Bar --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" id="searchEducation" class="form-control border-0 shadow-sm" placeholder="Cari tutorial (contoh: foto produk, pengiriman)..." style="height: 50px;">
                        <div class="input-group-append">
                            <span class="input-group-text bg-white border-0 shadow-sm px-3">
                                <i class="fa fa-search text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="educationList">
                {{-- List Artikel --}}
                @forelse($articles as $article)
                <div class="col-md-4 mb-4 education-card">
                    <div class="card h-100 border-0 shadow-sm p-3">
                        <div class="card-body">
                            <div class="icon-edu mb-3 text-primary">
                                <i class="fa {{ $article['icon'] ?? 'fa-book' }} fa-2x"></i>
                            </div>
                            <span class="badge badge-pill badge-light mb-2">{{ $article['category'] }}</span>
                            <h5 class="font-weight-bold card-title">{{ $article['title'] }}</h5>
                            <p class="text-muted small card-text">{{ $article['desc'] }}</p>
                            <hr>
                            <a href="#" class="btn btn-link p-0 text-primary font-weight-bold small">
                                Baca Selengkapnya <i class="fa fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Belum ada konten edukasi saat ini.</p>
                </div>
                @endforelse
            </div>
            
            {{-- Pesan jika pencarian tidak ditemukan --}}
            <div id="noResults" class="row d-none">
                <div class="col-12 text-center py-5">
                    <img src="/images/empty-search.svg" alt="" style="height: 150px;" class="mb-3">
                    <h5 class="text-muted">Tutorial tidak ditemukan. Coba kata kunci lain.</h5>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('addon-script')
<script>
    $(document).ready(function(){
        $("#searchEducation").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            var visibleCards = 0;

            $("#educationList .education-card").filter(function() {
                // Cari berdasarkan judul atau deskripsi
                var match = $(this).find('.card-title').text().toLowerCase().indexOf(value) > -1 || 
                            $(this).find('.card-text').text().toLowerCase().indexOf(value) > -1;
                
                $(this).toggle(match);
                
                if(match) visibleCards++;
            });

            // Tampilkan pesan "Tidak Ditemukan" jika tidak ada kartu yang cocok
            if(visibleCards === 0) {
                $("#noResults").removeClass("d-none");
            } else {
                $("#noResults").addClass("d-none");
            }
        });
    });
</script>
@endpush