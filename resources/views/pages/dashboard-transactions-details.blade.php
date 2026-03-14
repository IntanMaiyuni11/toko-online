@extends('layouts.dashboard')

@section('title')
    Store Dashboard Transaction Detail
@endsection

@section('content')
<div class="section-content section-dashboard-home" data-aos="fade-up">
    <div class="container-fluid">
        <div class="dashboard-heading">
            <h2 class="dashboard-title">#{{ $transaction->transaction->code }}</h2>
            <p class="dashboard-subtitle">Transaction Details</p>
        </div>
        <div class="dashboard-content" id="transactionDetails">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            {{-- Baris Navigasi & Tombol Batal --}}
                            <div class="row mb-4">
                                <div class="col-12 d-flex justify-content-between align-items-center">
                                    <a href="{{ route('dashboard-transaction') }}" class="btn btn-sm btn-outline-secondary px-4">
                                        &larr; Kembali
                                    </a>

                                    @if($transaction->product->users_id != Auth::user()->id) 
                                        @if(in_array($transaction->shipping_status, ['PENDING', 'SHIPPING']))
                                            <form action="{{ route('dashboard-transaction-update', $transaction->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="shipping_status" value="CANCELLED">
                                                <button type="submit" class="btn btn-sm btn-danger px-4" 
                                                        onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                                    Batalkan Pesanan
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            {{-- DAFTAR PRODUK (LOOPING) --}}
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5>Daftar Produk ({{ $all_products->count() }})</h5>
                                    <hr>
                                    @foreach ($all_products as $product)
                                        <div class="row align-items-center mb-3">
                                            <div class="col-12 col-md-2">
                                                <img src="{{ Storage::url($product->product->galleries->first()->photos ?? '') }}"
                                                     alt="" class="w-100 rounded" />
                                            </div>
                                            <div class="col-12 col-md-10">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="product-title">Product Name</div>
                                                        <div class="product-subtitle">{{ $product->product->name }}</div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="product-title">Price</div>
                                                        <div class="product-subtitle">Rp {{ number_format($product->price) }}</div>
                                                    </div>
                                                    <div class="col-md-4 text-md-right">
                                                        {{-- Cek Jika Pembeli ingin memberi ulasan per produk --}}
                                                        @if($product->shipping_status == 'SUCCESS' && $transaction->product->users_id != Auth::user()->id)
                                                            <a href="{{ route('details', $product->product->slug) }}#review" class="btn btn-sm btn-primary">Beri Ulasan</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- Informasi Transaksi & Pengiriman --}}
                            <div class="row">
                                <div class="col-12 col-md-4 mb-3">
                                    <div class="product-title">Customer Name</div>
                                    <div class="product-subtitle">{{ $transaction->transaction->user->name }}</div>
                                </div>
                                <div class="col-12 col-md-4 mb-3">
                                    <div class="product-title">Date of Transaction</div>
                                    <div class="product-subtitle">{{ $transaction->created_at->translatedFormat('d F Y, H:i') }} WIB</div>
                                </div>
                                <div class="col-12 col-md-4 mb-3">
                                    <div class="product-title">Mobile</div>
                                    <div class="product-subtitle">{{ $transaction->transaction->user->phone_number }}</div>
                                </div>
                                <div class="col-12 col-md-4 mb-3">
                                    <div class="product-title">Payment Status</div>
                                    <div class="product-subtitle text-danger font-weight-bold">
                                        {{ $transaction->transaction->transaction_status }}
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 mb-3">
                                    <div class="product-title">Total Amount (Incl. Tax/Ship)</div>
                                    <div class="product-subtitle font-weight-bold text-success">
                                        Rp {{ number_format($transaction->transaction->total_price ) }}
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h5>Shipping Information</h5>

                            @if($transaction->product->users_id == Auth::user()->id)
                                {{-- KHUSUS PENJUAL: Form Update Massal --}}
                                <form action="{{ route('dashboard-transaction-update', $transaction->id) }}" method="POST">
                                    @csrf
                                    <div class="row mt-3">
                                        <div class="col-12 col-md-6 mb-3">
                                            <div class="product-title">Address 1</div>
                                            <div class="product-subtitle">{{ $transaction->transaction->user->address_one }}</div>
                                        </div>
                                        <div class="col-12 col-md-6 mb-3">
                                            <div class="product-title">Address 2</div>
                                            <div class="product-subtitle">{{ $transaction->transaction->user->address_two }}</div>
                                        </div>

                                        <div class="col-12 mt-4">
                                            <h6 class="text-primary">Update Status & Resi untuk Semua Produk</h6>
                                        </div>

                                        <div class="col-12 col-md-4 mb-3">
                                            <div class="product-title">Shipping Status</div>
                                            <select name="shipping_status" id="status" class="form-control" v-model="status">
                                                <option value="PENDING">Pending</option>
                                                <option value="SHIPPING">Shipping</option>
                                                <option value="SUCCESS">Success</option>
                                            </select>
                                        </div>

                                        <template v-if="status == 'SHIPPING'">
                                            <div class="col-12 col-md-4 mb-3">
                                                <div class="product-title">Kurir</div>
                                                <input class="form-control" type="text" name="code" value="{{ $transaction->code }}" placeholder="Contoh: JNE" />
                                            </div>
                                            <div class="col-12 col-md-4 mb-3">
                                                <div class="product-title">Nomor Resi</div>
                                                <input class="form-control" type="text" name="resi" v-model="resi" placeholder="Masukkan resi" />
                                            </div>
                                        </template>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-12 text-right">
                                            <button type="submit" class="btn btn-success btn-lg px-5">
                                                Save Updates
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @else
                                {{-- KHUSUS PEMBELI: Tampilan Read Only --}}
                                <div class="row mt-3">
                                    <div class="col-12 col-md-6 mb-3">
                                        <div class="product-title">Full Address</div>
                                        <div class="product-subtitle">
                                            {{ $transaction->transaction->user->address_one }}, 
                                            {{ App\Models\Regency::find($transaction->transaction->user->regencies_id)->name ?? '-' }},
                                            {{ App\Models\Province::find($transaction->transaction->user->provinces_id)->name ?? '-' }}
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3 mb-3">
                                        <div class="product-title">Shipping Status</div>
                                        <div class="product-subtitle @if($transaction->shipping_status == 'SUCCESS') text-success @else text-warning @endif font-weight-bold">
                                            {{ $transaction->shipping_status }}
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3 mb-3">
                                        <div class="product-title">Resi / Kurir</div>
                                        <div class="product-subtitle font-weight-bold">
                                            {{ $transaction->resi ?? 'Belum ada resi' }} ({{ $transaction->code ?? '-' }})
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('addon-script')
<script src="/vendor/vue/vue.js"></script>
<script>
    var transactionDetails = new Vue({
        el: "#transactionDetails",
        data: {
            status: "{{ $transaction->shipping_status }}",
            resi: "{{ $transaction->resi }}",
        },
    });
</script>
@endpush