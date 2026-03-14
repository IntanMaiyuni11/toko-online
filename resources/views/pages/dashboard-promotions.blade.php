@extends('layouts.dashboard')

@section('title', 'Promosi')

@section('content')
<div class="section-content section-dashboard-home" data-aos="fade-up">
    <div class="container-fluid">
        <div class="dashboard-content">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center mt-5">
                    @if(Auth::user()->roles == 'CUSTOMER')
                        {{-- TAMPILAN UNTUK CUSTOMER --}}
                        <div class="card border-0 shadow-sm p-4">
                            <img src="/images/ic_seller_mode.svg" class="w-50 mx-auto mb-4" alt="">
                            <h4>Ingin mempromosikan produkmu?</h4>
                            <p class="text-muted">
                                Halaman promosi hanya tersedia untuk Seller. Ayo mulai jualan sekarang dan kembangkan bisnismu!
                            </p>
                            <a href="{{ route('dashboard-settings-store') }}" class="btn btn-success px-4">
                                Mulai Jadi Seller
                            </a>
                        </div>
                    @else
                        {{-- TAMPILAN UNTUK SELLER (ASLI) --}}
                        <div class="card border-0 shadow-sm p-4">
                            <h5>Belum ada promosi berjalan</h5>
                            <p class="text-muted">Kelola promosi tokomu di sini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection