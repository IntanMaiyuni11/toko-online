@extends('layouts.dashboard')

@section('title', 'Rewards System')

@push('addon-style')
<style>
    /* Memberikan jarak aman agar tidak tertutup Navbar Fixed */
    .section-content {
        margin-top: 40px; 
    }
    
    .bg-gradient-primary {
        background: linear-gradient(45deg, #2980b9, #3498db);
    }
    .bg-primary-light {
        background-color: rgba(52, 152, 219, 0.1);
    }
    .rounded-lg {
        border-radius: 12px !important;
    }
    .card-reward {
        transition: all 0.3s ease;
        border: 1px solid #eee;
    }
    .card-reward:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
        border-color: #3498db;
    }
</style>
@endpush

@section('content')
<div class="section-content section-dashboard-home pt-5" data-aos="fade-up">
    <div class="container-fluid">
        <div class="dashboard-heading d-flex justify-content-between align-items-center mt-md-3">
            <div>
                <h2 class="dashboard-title">Rewards & Promosi</h2>
                <p class="dashboard-subtitle">Tukarkan poinmu atau kelola voucher untuk tokomu</p>
            </div>
            @if(Auth::user()->roles == 'USER')
                <a href="{{ route('dashboard-rewards-create') }}" class="btn btn-success px-4 shadow-sm">
                    + Tambah Reward Toko
                </a>
            @endif
        </div>

        <div class="dashboard-content mt-4">
            <div class="row">
                {{-- Daftar Voucher --}}
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="mb-4 font-weight-bold">Daftar Voucher Tersedia</h5>
                            <div class="row">
                                @foreach($rewards as $reward)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 rounded-lg shadow-none card-reward">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="p-2 bg-primary-light rounded mr-3 text-primary">
                                                    <i class="fas fa-ticket-alt"></i>
                                                </div>
                                                <div class="overflow-hidden">
                                                    <h6 class="mb-0 font-weight-bold text-truncate">{{ $reward->name }}</h6>
                                                    <small class="text-muted font-weight-bold">{{ number_format($reward->points) }} Pts</small>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                {{-- Semua User bisa tukar --}}
                                                <form action="{{ route('rewards-redeem', $reward->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-primary px-3 rounded-pill" 
                                                        {{ Auth::user()->points < $reward->points ? 'disabled' : '' }}>
                                                        <i class="fas fa-shopping-cart mr-1"></i> Tukar
                                                    </button>
                                                </form>

                                                {{-- Tombol Edit tetap ada tanpa label 'Milik Saya' --}}
                                                @if(Auth::user()->roles == 'USER' && Auth::user()->id == $reward->users_id)
                                                    <a href="{{ route('dashboard-rewards-edit', $reward->id) }}" class="btn btn-sm btn-info rounded-pill px-3">
                                                        <i class="fas fa-edit mr-1"></i> Edit
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sidebar Info Poin --}}
                <div class="col-md-4">
                    <div class="card bg-gradient-primary text-white border-0 shadow-sm mb-4">
                        <div class="card-body py-4 text-center">
                            <p class="mb-1 opacity-75 small">Saldo Poin Anda</p>
                            <h2 class="font-weight-bold mb-0">
                                <i class="fas fa-coins mr-2"></i>{{ number_format(Auth::user()->points) }}
                            </h2>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="mb-3 font-weight-bold">Riwayat Aktivitas</h6>
                            <div class="list-group list-group-flush">
                                @forelse($histories as $history)
                                <div class="list-group-item px-0 border-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div style="max-width: 75%;">
                                            <p class="mb-0 small font-weight-bold text-dark text-truncate">{{ $history->description }}</p>
                                            <small class="text-muted" style="font-size: 10px;">{{ $history->created_at->diffForHumans() }}</small>
                                        </div>
                                        <span class="{{ $history->amount < 0 ? 'text-danger' : 'text-success' }} font-weight-bold small">
                                            {{ $history->amount < 0 ? '' : '+' }}{{ number_format($history->amount) }}
                                        </span>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-4 text-muted small">
                                    Belum ada aktivitas
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection