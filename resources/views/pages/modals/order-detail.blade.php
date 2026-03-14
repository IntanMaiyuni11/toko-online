<div class="modal fade" id="detailModal{{ $order->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 12px; border: none;">
            <div class="modal-header border-bottom p-4">
                <h6 class="modal-title font-weight-bold">Detail Transaksi</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-0">
                <div class="row no-gutters">
                    <div class="col-md-8 p-4 border-right">
                        {{-- Ambil status dari transaction_details --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="font-weight-bold mb-0 text-success">
                                {{ str_replace('_', ' ', $order->shipping_status) }}
                            </h6>
                        </div>
                        
                        {{-- Data dari tabel Transactions (Induk) --}}
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">No. Invoice</span>
                            <span class="font-weight-bold text-success">{{ $order->transaction->code }}</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-3">
                            <span class="text-muted">Tanggal Pembelian</span>
                            <span>{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                        </div>
                        <hr>

                        <h6 class="font-weight-bold mb-3">Detail Produk</h6>
                        <div class="card border p-3 mb-3" style="border-radius: 10px;">
                            <div class="d-flex align-items-center">
                                <img src="{{ Storage::url($order->product->galleries->first()->photos ?? '') }}" style="width: 50px; height: 50px; object-fit: cover;" class="rounded">
                                <div class="ml-3">
                                    <div class="font-weight-bold small text-dark">{{ $order->product->name }}</div>
                                    <div class="text-muted small">1 x Rp {{ number_format($order->price, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>

                        <h6 class="font-weight-bold mb-3">Info Pengiriman</h6>
                        <div class="row small mb-2">
                            <div class="col-3 text-muted">Kurir</div>
                            <div class="col-9">: Standard Delivery</div>
                        </div>
                        <div class="row small mb-2">
                            <div class="col-3 text-muted">No Resi</div>
                            {{-- Ambil kolom resi dari transaction_details --}}
                            <div class="col-9">: <strong class="text-primary">{{ $order->resi ?? 'Resi Belum Tersedia' }}</strong></div>
                        </div>
                        <div class="row small">
                            <div class="col-3 text-muted">Alamat</div>
                            <div class="col-9">: <strong>{{ Auth::user()->name }}</strong><br>
                                {{ Auth::user()->address_one }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 p-4 d-flex flex-column bg-light">
                        <button class="btn btn-success btn-block font-weight-bold mb-2" data-toggle="modal" data-target="#lacakModal{{ $order->id }}" data-dismiss="modal">Lacak</button>
                        <a href="{{ route('dashboard-chat', ['user_id' => $order->product->users_id, 'product_id' => $order->product->id]) }}" 
                    class="btn btn-outline-secondary btn-block font-weight-bold">
                        <i class="fas fa-comments mr-1"></i> Chat Penjual
                    </a>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top p-4">
                <div class="w-100">
                    <h6 class="font-weight-bold mb-2">Rincian Pembayaran</h6>
                    <div class="d-flex justify-content-between small">
                        <span class="text-muted">Harga Produk</span>
                        <span>Rp {{ number_format($order->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span class="text-muted">Biaya Pengiriman</span>
                        <span>Rp {{ number_format($order->transaction->shipping_price, 0, ',', '.') }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between font-weight-bold">
                        <span>Total Belanja</span>
                        <span class="text-success">Rp {{ number_format($order->price + $order->transaction->shipping_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>