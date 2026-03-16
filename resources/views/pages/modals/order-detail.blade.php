<div class="modal fade" id="detailModal{{ $order->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg border-0" role="document">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-header border-0 p-4">
                <h5 class="modal-title font-weight-bold">Detail Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 pt-0">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">No. Invoice</label>
                        <div class="text-success font-weight-bold">{{ $order->transaction->code }}</div>
                    </div>
                    <div class="col-md-6 text-md-right">
                        <label class="text-muted small mb-1">Tanggal Pembelian</label>
                        <div>{{ $order->created_at->format('d F Y, H:i') }} WIB</div>
                    </div>
                </div>

                <h6 class="font-weight-bold mb-3">Detail Produk</h6>
                <div class="card bg-light border-0 mb-4" style="border-radius: 10px;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <img src="{{ $order->product->galleries->first() ? Storage::url($order->product->galleries->first()->photos) : '/images/default-product.jpg' }}" 
                                 class="rounded mr-3" style="width: 60px; height: 60px; object-fit: cover;">
                            <div>
                                <div class="font-weight-bold">{{ $order->product->name }}</div>
                                <div class="text-muted small">1 x Rp {{ number_format($order->price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <h6 class="font-weight-bold mb-3">Info Pengiriman</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small mb-0">Kurir</label>
                        <p class="mb-2">JNE - Reguler</p>
                        <label class="text-muted small mb-0">No. Resi</label>
                        <p class="font-weight-bold">{{ $order->resi ?? 'Belum ada resi' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small mb-0">Alamat</label>
                        <p class="small mb-0">{{ Auth::user()->address_one }}, {{ Auth::user()->address_two }}</p>
                    </div>
                </div>

                <hr>

                <h6 class="font-weight-bold mb-3">Rincian Pembayaran</h6>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Harga (1 Barang)</span>
                    <span>Rp {{ number_format($order->price, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Ongkos Kirim</span>
                    <span>Rp 0</span>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <span class="font-weight-bold">Total Belanja</span>
                    <span class="font-weight-bold text-success" style="font-size: 18px;">Rp {{ number_format($order->price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>