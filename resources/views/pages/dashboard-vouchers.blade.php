@extends('layouts.app') 

@section('content')
<div class="page-content page-cart">
    <section class="store-breadcrumbs" data-aos="fade-down" data-aos-delay="100">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item active">
                                My Vouchers
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <section class="store-cart">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2>Voucher Saya</h2>
                    <p>Gunakan kode di bawah ini saat checkout untuk mendapatkan potongan harga.</p>
                </div>
            </div>
            <div class="row mt-4">
                @forelse($vouchers as $voucher)
                    <div class="col-md-4 mb-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h4 class="text-success font-weight-bold">{{ $voucher->code }}</h4>
                                <hr>
                                <p class="mb-1">Potongan: <strong>Rp {{ number_format($voucher->discount_amount, 0, ',', '.') }}</strong></p>
                                <span class="badge {{ $voucher->is_used ? 'badge-secondary' : 'badge-success' }}">
                                    {{ $voucher->is_used ? 'Sudah Digunakan' : 'Tersedia' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p>Kamu belum memiliki voucher. Yuk tukar poinmu!</p>
                        <a href="{{ route('rewards') }}" class="btn btn-success">Ke Halaman Rewards</a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
@endsection