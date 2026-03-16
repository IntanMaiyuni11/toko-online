<div class="modal fade" id="lacakModal{{ $order->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog border-0" role="document">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="modal-title font-weight-bold">Lacak Pesanan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="tracking-list">
                    {{-- Status: Selesai --}}
                    <div class="tracking-item {{ $order->shipping_status == 'SUCCESS' ? 'active' : '' }} mb-4 d-flex">
                        <div class="mr-3 text-center" style="width: 25px;">
                            <i class="fas fa-check-circle {{ $order->shipping_status == 'SUCCESS' ? 'text-success' : 'text-muted' }}"></i>
                            <div style="width: 2px; height: 100%; background: #eee; margin: 0 auto;"></div>
                        </div>
                        <div>
                            <div class="font-weight-bold small">Pesanan Selesai</div>
                            <div class="text-muted x-small">Pesanan telah diterima oleh pembeli.</div>
                        </div>
                    </div>

                    {{-- Status: Dikirim --}}
                    <div class="tracking-item {{ $order->shipping_status == 'SHIPPING' ? 'active' : '' }} mb-4 d-flex">
                        <div class="mr-3 text-center" style="width: 25px;">
                            <i class="fas fa-truck {{ $order->shipping_status == 'SHIPPING' ? 'text-success' : 'text-muted' }}"></i>
                            <div style="width: 2px; height: 100%; background: #eee; margin: 0 auto;"></div>
                        </div>
                        <div>
                            <div class="font-weight-bold small">Dalam Pengiriman</div>
                            <div class="text-muted x-small">Resi: {{ $order->resi ?? '-' }}</div>
                        </div>
                    </div>

                    {{-- Status: Diproses --}}
                    <div class="tracking-item active d-flex">
                        <div class="mr-3 text-center" style="width: 25px;">
                            <i class="fas fa-box text-success"></i>
                        </div>
                        <div>
                            <div class="font-weight-bold small">Pesanan Diproses</div>
                            <div class="text-muted x-small">{{ $order->created_at->format('d M, H:i') }} WIB</div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-light rounded small">
                    <i class="fas fa-info-circle text-primary mr-1"></i> 
                    Estimasi tiba tergantung pada kinerja kurir pilihan Anda.
                </div>
            </div>
        </div>
    </div>
</div>