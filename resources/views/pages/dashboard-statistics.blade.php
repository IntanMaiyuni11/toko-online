@extends('layouts.dashboard')

@section('title', 'Statistics')

@section('content')
{{-- Tambahkan class 'pt-5' atau margin top manual di sini --}}
<div class="section-content section-dashboard-home" data-aos="fade-up" style="margin-top: 50px;">
    <div class="container-fluid">
        <div class="dashboard-heading mb-5"> {{-- Tambah mb-5 agar ada jarak ke kartu di bawahnya --}}
            <div class="row align-items-center">
                <div class="col-md-7">
                    <h2 class="dashboard-title">Statistik Penjualan</h2>
                    <p class="dashboard-subtitle">Pantau grafik pertumbuhan tokomu tahun {{ $selectedYear }}</p>
                </div>
                <div class="col-md-5 text-right">
                    {{-- Bungkus dropdown agar tidak menabrak profil --}}
                    <div class="filter-container mt-2">
                        <form action="{{ route('dashboard-statistics') }}" method="GET" id="filterForm">
                            <span class="mr-2 d-none d-md-inline text-muted">Pilih Periode:</span>
                            <select name="year" class="form-control shadow-sm d-inline-block w-auto" onchange="document.getElementById('filterForm').submit()">
                                @foreach($availableYears as $year)
                                    <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                        Tahun {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-content">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-3 mb-3">
                        <h6 class="text-muted">Total Pendapatan ({{ $selectedYear }})</h6>
                        <h3 class="text-success font-weight-bold">Rp {{ number_format($data->sum(), 0, ',', '.') }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-3 mb-3">
                        <h6 class="text-muted">Rata-rata Per Bulan</h6>
                        <h3 class="text-primary font-weight-bold">Rp {{ number_format($data->count() > 0 ? $data->sum() / 12 : 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-3 mb-3">
                        <h6 class="text-muted">Bulan Terbaik</h6>
                        <h3 class="text-warning font-weight-bold">
                            {{ $labels[$data->search($data->max())] ?? '-' }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="mb-4 font-weight-bold">Grafik Pendapatan Bulanan</h5>
                <div style="height: 350px;"> 
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('addon-script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    // Buat gradien warna agar grafik lebih modern
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(56, 193, 114, 0.4)');
    gradient.addColorStop(1, 'rgba(56, 193, 114, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Pendapatan (IDR)',
                data: {!! json_encode($data) !!},
                backgroundColor: gradient, // Gunakan gradien
                borderColor: '#38c172',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#38c172',
                pointBorderColor: '#fff',
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Penting agar mengikuti height div pembungkus
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    grid: { color: '#f3f3f3' },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        },
                        stepSize: 100 // Bisa disesuaikan agar grid tidak terlalu rapat
                    }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
</script>
@endpush