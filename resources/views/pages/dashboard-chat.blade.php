@extends('layouts.dashboard')

@section('title', 'Chat - Dashboard')

@section('content')
<div class="section-content section-dashboard-home">
    <div class="container-fluid">

        <div class="dashboard-heading d-flex justify-content-between align-items-center mb-4">
            <h2 class="dashboard-title mb-0">Chat</h2>
        </div>

        <div class="dashboard-content">
            <div class="row no-gutters bg-white shadow rounded-3 overflow-hidden" style="height: 80vh;">

                <div class="col-md-4 bg-light-sidebar border-right">
                    <div class="p-3 border-bottom bg-white">
                        <h6 class="mb-0 font-weight-bold">Kotak Masuk</h6>
                    </div>

                    <div class="chat-sidebar-list p-2" style="height: calc(80vh - 70px); overflow-y: auto;">
                        @forelse($contacts as $contact)
                            <a href="{{ route('dashboard-chat', ['user_id' => $contact->id]) }}"
                               class="d-flex align-items-center p-2 mb-2 text-decoration-none contact-item
                               {{ request('user_id') == $contact->id ? 'active-chat' : '' }}">

                                <img src="{{ $contact->photos ? Storage::url($contact->photos) : asset('images/icon-user.png') }}"
                                     class="rounded-circle mr-3"
                                     width="45" height="45"
                                     style="object-fit: cover;">

                                <div class="flex-grow-1 overflow-hidden">
                                    <strong class="d-block text-dark">{{ $contact->name }}</strong>
                                    <div class="d-flex align-items-center">
                                        <small class="text-muted text-truncate">
                                            {{ $contact->store_name ?? 'Pelanggan' }}
                                        </small>
                                    </div>
                                </div>
                            </a> {{-- Penutup tag A yang tadi hilang --}}
                        @empty
                            <div class="text-center mt-5 text-muted">
                                <small>Tidak ada kontak</small>
                            </div>
                        @endforelse {{-- Penutup forelse yang tadi hilang --}}
                    </div>
                </div>

                <div class="col-md-8 d-flex flex-column">

                    @if(isset($activeContact) && $activeContact != null)

                        <div class="p-3 border-bottom d-flex align-items-center bg-white">
                            <img src="{{ $activeContact->photos ? Storage::url($activeContact->photos) : asset('images/icon-user.png') }}"
                                 class="rounded-circle mr-3"
                                 width="40" height="40">

                            <div>
                                <strong>{{ $activeContact->store_name ?? $activeContact->name }}</strong>
                                <div class="small text-success">
                                    <i class="fas fa-circle mr-1" style="font-size:6px;"></i> Online
                                </div>
                            </div>
                        </div>

                        <div class="flex-grow-1 p-4 chat-bg" id="chatWindow" style="overflow-y: auto;">
                            @foreach($messages as $msg)
                                <div class="d-flex mb-3 {{ $msg->sender_id == Auth::id() ? 'justify-content-end' : 'justify-content-start' }}">
                                    <div style="max-width: 70%;">
                                        
                                        @if($msg->products_id && $msg->product)
                                            <div class="product-card-chat mb-2 shadow-sm">
                                                <img src="{{ $msg->product->galleries->first() ? Storage::url($msg->product->galleries->first()->photos) : '' }}">
                                                <div class="p-2 bg-white">
                                                    <div class="small font-weight-bold text-truncate text-dark">
                                                        {{ $msg->product->name }}
                                                    </div>
                                                    <div class="small text-success font-weight-bold">
                                                        Rp {{ number_format($msg->product->price) }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="bubble {{ $msg->sender_id == Auth::id() ? 'bubble-me' : 'bubble-them' }}">
                                            {{ $msg->message }}
                                            <div class="bubble-meta text-right">
                                                {{ $msg->created_at->format('H:i') }}
                                                @if($msg->sender_id == Auth::id())
                                                    <i class="fas fa-check-double ml-1 {{ $msg->is_read ? 'text-light' : 'text-white-50' }}"></i>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="p-3 border-top bg-white">
                            @if(request('product_id') && isset($selectedProduct))
                                <div class="product-preview mb-3 p-2 border rounded d-flex align-items-center bg-light">
                                    <img src="{{ $selectedProduct->galleries->first() ? Storage::url($selectedProduct->galleries->first()->photos) : '' }}" 
                                        class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                    <div class="ml-3 flex-grow-1">
                                        <div class="small font-weight-bold">{{ $selectedProduct->name }}</div>
                                        <div class="small text-success">Rp {{ number_format($selectedProduct->price) }}</div>
                                    </div>
                                    <a href="{{ route('dashboard-chat', ['user_id' => $activeContact->id]) }}" class="text-danger ml-2">
                                        <i class="fas fa-times-circle"></i>
                                    </a>
                                </div>
                            @endif

                            <form action="{{ route('dashboard-chat-send') }}" method="POST">
                                @csrf
                                <input type="hidden" name="receiver_id" value="{{ $activeContact->id }}">
                                @if(request('product_id'))
                                    <input type="hidden" name="products_id" value="{{ request('product_id') }}">
                                @endif

                                <div class="d-flex align-items-center bg-light rounded-pill px-3 py-2">
                                    <input type="text" name="message" class="form-control bg-transparent border-0 shadow-none"
                                           placeholder="Tulis pesan..." required autocomplete="off">
                                    <button type="submit" class="btn btn-success rounded-circle ml-2">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                    @else
                        <div class="h-100 d-flex flex-column align-items-center justify-content-center text-center text-muted">
                            <img src="{{ asset('images/Chat.jpg') }}" style="width: 200px;" class="mb-3">
                            <h5 class="font-weight-bold">Pilih kontak untuk mulai chat</h5>
                            <small>Kamu bisa bertanya tentang produk atau pesanan.</small>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('addon-style')
<style>
.bg-light-sidebar { background: #f8f9fa; }
.contact-item { border-radius: 10px; transition: 0.2s; color: inherit; }
.contact-item:hover { background: #e9ecef; }
.active-chat { background: #ffffff !important; border-left: 4px solid #28a745; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
.chat-bg { background: #f5f7fb; }
.bubble { padding: 10px 14px; border-radius: 16px; font-size: 14px; line-height: 1.4; }
.bubble-me { background: #28a745; color: white; border-bottom-right-radius: 2px; }
.bubble-them { background: #ffffff; border: 1px solid #eaeaea; border-bottom-left-radius: 2px; color: #333; }
.bubble-meta { font-size: 10px; margin-top: 5px; opacity: 0.7; }
.product-card-chat { width: 200px; border-radius: 10px; overflow: hidden; background: white; border: 1px solid #eaeaea; }
.product-card-chat img { height: 120px; width: 100%; object-fit: cover; }
</style>
@endpush

@push('addon-script')
<script>
    var chatWindow = document.getElementById("chatWindow");
    if(chatWindow) {
        chatWindow.scrollTop = chatWindow.scrollHeight;
    }
</script>
@endpush