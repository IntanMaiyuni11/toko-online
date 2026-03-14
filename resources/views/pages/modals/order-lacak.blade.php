<div class="modal fade" id="lacakModal{{ $order->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px; border: none;">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="modal-title font-weight-bold">Lacak Pengiriman</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4 p-3 bg-light rounded">
                    <div class="small text-muted text-uppercase">Nomor Resi</div>
                    <div class="font-weight-bold text-dark">{{ $order->resi ?? 'DALAM PROSES' }}</div>
                </div>

                <div class="tracking-wrapper position-relative">
                    {{-- GARIS VERTIKAL BACKGROUND --}}
                    <div style="position: absolute; left: 69px; top: 10px; bottom: 10px; width: 2px; background: #e0e0e0; z-index: 1;"></div>

                    {{-- STATUS: SUCCESS --}}
                    @if($order->shipping_status == 'SUCCESS')
                    <div class="d-flex align-items-start mb-4 position-relative" style="z-index: 2;">
                        <div class="text-muted small text-right mr-3" style="min-width: 60px;">{{ $order->updated_at->format('H:i') }}<br>{{ $order->updated_at->format('d M') }}</div>
                        <div class="bg-success rounded-circle border border-white" style="width: 14px; height: 14px; margin-top: 4px;"></div>
                        <div class="ml-3 small">
                            <strong class="d-block text-success">Pesanan Selesai</strong>
                            <span class="text-muted">Paket telah diterima. Terima kasih telah berbelanja!</span>
                        </div>
                    </div>
                    @endif

                    {{-- STATUS: SHIPPING / ON_PROGRESS --}}
                    @if(in_array($order->shipping_status, ['SHIPPING', 'ON_PROGRESS', 'SUCCESS']))
                    <div class="d-flex align-items-start mb-4 position-relative" style="z-index: 2;">
                        <div class="text-muted small text-right mr-3" style="min-width: 60px;">{{ $order->created_at->addHours(1)->format('H:i') }}<br>{{ $order->created_at->format('d M') }}</div>
                        <div class="bg-primary rounded-circle border border-white" style="width: 14px; height: 14px; margin-top: 4px;"></div>
                        <div class="ml-3 small">
                            <strong class="d-block">Paket Dalam Pengiriman</strong>
                            <span class="text-muted">Pesanan sedang dalam perjalanan oleh kurir.</span>
                        </div>
                    </div>
                    @endif

                    {{-- STATUS: PENDING (SELALU ADA SEBAGAI START) --}}
                    <div class="d-flex align-items-start position-relative" style="z-index: 2;">
                        <div class="text-muted small text-right mr-3" style="min-width: 60px;">{{ $order->created_at->format('H:i') }}<br>{{ $order->created_at->format('d M') }}</div>
                        <div class="bg-secondary rounded-circle border border-white" style="width: 14px; height: 14px; margin-top: 4px;"></div>
                        <div class="ml-3 small">
                            <strong class="d-block text-muted">Pesanan Diproses</strong>
                            <span class="text-muted">Penjual sedang menyiapkan pesanan Anda.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>