@extends('layouts.app')

@section('title')
    Store Rewards Page
@endsection

@section('content')
    <div class="page-content page-rewards">
      <section class="store-rewards">
        <div class="container">
          
          @if(session('success'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                  {{ session('success') }}
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
          @endif
          @if(session('error'))
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  {{ session('error') }}
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
          @endif

          <div class="row">
            <div class="col-12" data-aos="fade-up">
              <h4 class="mb-2">Rewards</h4>
              <p class="text-muted">
                Kumpulkan poin dari setiap transaksi dan tukarkan dengan hadiah menarik.
              </p>
            </div>
          </div>

          <div class="row mb-5" data-aos="fade-up">
            <div class="col-lg-8 mx-auto">
              <div class="points-card">
                <div>
                  <h6>Total Poin Kamu</h6>
                  <h2>{{ number_format(Auth::user()->points) }} Points</h2>

                  @php
                    // Logika sederhana untuk progress bar (misal target reward terdekat adalah 5000)
                    $target = 5000;
                    $percentage = min((Auth::user()->points / $target) * 100, 100);
                  @endphp

                  <div class="progress mt-3">
                    <div class="progress-bar" style="width: {{ $percentage }}%"></div>
                  </div>

                  @if(Auth::user()->points < $target)
                    <small>{{ number_format($target - Auth::user()->points) }} poin lagi untuk reward spesial berikutnya 🎁</small>
                  @else
                    <small>Kamu hebat! Poinmu sudah melampaui target utama! 🔥</small>
                  @endif
                </div>

                <div class="points-icon">
                  <i class="bi bi-stars"></i>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            @forelse ($rewards as $reward)
                <div class="col-6 col-md-4 col-lg-3" data-aos="fade-up">
                    <div class="card-reward {{ Auth::user()->points < $reward->points ? 'locked' : '' }}">
                        <div class="reward-image">
                            <img src="{{ $reward->image ?? 'https://img.icons8.com/fluency/240/gift--v1.png' }}" alt="{{ $reward->name }}" />
                        </div>
                        <div class="reward-body">
                            <h5>{{ $reward->name }}</h5>
                            <p class="points-large">{{ number_format($reward->points) }} Points</p>
                            
                            @if(Auth::user()->points >= $reward->points)
                                <form action="{{ route('rewards-redeem', $reward->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-block" onclick="return confirm('Apakah kamu yakin ingin menukarkan poin untuk {{ $reward->name }}?')">
                                        Tukarkan
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-secondary btn-block" disabled>Poin Kurang</button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p>Belum ada reward yang tersedia saat ini.</p>
                </div>
            @endforelse
            </div>

          <div class="row mt-5">
            <div class="col-12" data-aos="fade-up">
                <h5 class="mb-3">Riwayat Poin</h5>
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr class="text-muted">
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                        <th>Jumlah Poin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(Auth::user()->pointHistories as $history)
                                    <tr>
                                        <td>{{ $history->created_at->format('d M Y, H:i') }}</td>
                                        <td>{{ $history->description }}</td>
                                        <td class="{{ $history->amount > 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $history->amount > 0 ? '+' : '' }}{{ number_format($history->amount) }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">Belum ada riwayat poin.</td>
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
      </section>
    </div>
@endsection