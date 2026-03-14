@extends('layouts.dashboard')

@section('title', 'Logistics Settings')

@section('content')
<div class="section-content section-dashboard-home" data-aos="fade-up">
    <div class="container-fluid">
        <div class="dashboard-heading">
            <h2 class="dashboard-title">Pengaturan Logistik</h2>
            <p class="dashboard-subtitle">Aktifkan kurir yang ingin kamu sediakan untuk pembeli</p>
        </div>
        <div class="dashboard-content">
            <form action="{{ route('dashboard-logistics-update') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-12 col-md-8">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                {{-- Item Kurir 1 --}}
                                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3 p-2 bg-light rounded">
                                            <i class="fas fa-truck text-primary" style="font-size: 24px;"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 font-weight-bold">J&T Express</h6>
                                            <small class="text-muted">Pengiriman reguler dan express</small>
                                        </div>
                                    </div>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="kurir_jt" class="custom-control-input" id="kurir1" checked>
                                        <label class="custom-control-label" for="kurir1"></label>
                                    </div>
                                </div>

                                {{-- Item Kurir 2 --}}
                                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3 p-2 bg-light rounded">
                                            <i class="fas fa-shipping-fast text-danger" style="font-size: 24px;"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 font-weight-bold">SiCepat</h6>
                                            <small class="text-muted">Layanan pengiriman super cepat</small>
                                        </div>
                                    </div>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="kurir_sicepat" class="custom-control-input" id="kurir2">
                                        <label class="custom-control-label" for="kurir2"></label>
                                    </div>
                                </div>

                                {{-- Item Kurir 3 --}}
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3 p-2 bg-light rounded">
                                            <i class="fas fa-box text-warning" style="font-size: 24px;"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 font-weight-bold">Pos Indonesia</h6>
                                            <small class="text-muted">Menjangkau seluruh pelosok negeri</small>
                                        </div>
                                    </div>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="kurir_pos" class="custom-control-input" id="kurir3">
                                        <label class="custom-control-label" for="kurir3"></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TOMBOL SIMPAN DI BAWAH --}}
                        <div class="row mt-4">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn btn-success px-5 btn-lg">
                                    Simpan Pengaturan
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Informasi Tambahan --}}
                    <div class="col-12 col-md-4">
                        <div class="card border-0 shadow-sm bg-primary text-white">
                            <div class="card-body">
                                <h5>Info Logistik</h5>
                                <p class="small opacity-75">
                                    Kurir yang kamu aktifkan akan muncul sebagai pilihan saat pembeli melakukan checkout. Pastikan kamu sudah bekerja sama dengan kurir tersebut.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection