@extends('layouts.app')

@section('title')
    Store Detail Page
@endsection

@section('content')
    <div class="page-content page-details">
        <section class="store-breadcrumbs" data-aos="fade-down" data-aos-delay="100">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active">Product Details</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </section>

        <section class="store-gallery mb-3" id="gallery">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8" data-aos="zoom-in">
                        <transition name="slide-fade" mode="out-in">
                            <img :key="photos[activePhoto].id" :src="photos[activePhoto].url" class="w-100 main-image" alt="" />
                        </transition>
                    </div>
                    <div class="col-lg-2">
                        <div class="row">
                            <div class="col-3 col-lg-12 mt-2 mt-lg-0" v-for="(photo, index) in photos" :key="photo.id" data-aos="zoom-in" data-aos-delay="100">
                                <a href="#" @click="changeActive(index)">
                                    <img :src="photo.url" class="w-100 thumbnail-image" :class="{ active: index == activePhoto }" alt="" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="store-details-container" data-aos="fade-up">
            <section class="store-heading">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8">
                            <h1>{{ $product->name }}</h1>
                            <div class="owner">By {{ $product->user->store_name }}</div>
                            <div class="price">Rp {{ number_format($product->price) }}</div>
                        </div>
                        <div class="col-lg-2" data-aos="zoom-in">
                          @auth
                              {{-- Tombol Beli Langsung (Primary Action) --}}
                              <form action="{{ route('checkout-direct') }}" method="POST" enctype="multipart/form-data">
                                  @csrf
                                  <input type="hidden" name="products_id" value="{{ $product->id }}">
                                  <button
                                      type="submit"
                                      class="btn btn-success px-4 text-white btn-block mb-2 font-weight-bold"
                                  >
                                      Beli Langsung
                                  </button>
                              </form>

                              {{-- Tombol Keranjang (Secondary Action) --}}
                              <form action="{{ route('detail-add', $product->id) }}" method="POST" enctype="multipart/form-data">
                                  @csrf
                                  <button
                                      type="submit"
                                      class="btn btn-outline-success px-4 btn-block mb-3"
                                  >
                                      + Keranjang
                                  </button>
                              </form>

                              <a href="{{ route('dashboard-chat', ['user_id' => $product->users_id, 'product_id' => $product->id]) }}" 
                                  class="btn btn-link btn-block mb-3 text-success small">
                                  Tanya Penjual
                              </a>
                          @else
                              <a
                                  href="{{ route('login') }}"
                                  class="btn btn-success px-4 text-white btn-block mb-3"
                              >
                                  Sign in to Buy
                              </a>
                          @endauth
                      </div>
                    </div>
                </div>
            </section>

            <section class="store-description">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-lg-8">
                            {!! $product->description !!}
                        </div>
                    </div>
                </div>
            </section>

            <hr class="container">

            <section class="store-discussion">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-lg-8">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5>Diskusi ({{ count($product->discussions ?? []) }})</h5>
                                <button class="btn btn-sm btn-outline-success" data-toggle="collapse" data-target="#formDiskusi">Tanya Produk</button>
                            </div>

                            <div class="collapse mb-4" id="formDiskusi">
                                @auth
                                    <form action="{{ route('product-discussion-store', $product->id) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <textarea name="comment" class="form-control" rows="3" placeholder="Tulis pertanyaanmu di sini..."></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-success px-4">Kirim Pertanyaan</button>
                                    </form>
                                @else
                                    <p class="small text-muted">Silahkan <a href="{{ route('login') }}">login</a> untuk berdiskusi.</p>
                                @endauth
                            </div>

                            <div class="discussion-list">
                                @forelse($product->discussions ?? [] as $discussion)
                                    <div class="media mb-4">
                                        <img src="https://ui-avatars.com/api/?name={{ $discussion->user->name }}" class="mr-3 rounded-circle" style="width: 40px;">
                                        <div class="media-body">
                                            <div class="d-flex align-items-center">
                                                <h6 class="mt-0 mb-0 mr-2">{{ $discussion->user->name }}</h6>
                                                <small class="text-muted">{{ $discussion->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="text-dark small mb-2">{{ $discussion->comment }}</p>

                                            @if($discussion->replies->count() > 0)
                                                @foreach($discussion->replies as $reply)
                                                    <div class="media mt-3 border-left pl-3">
                                                        <img src="https://ui-avatars.com/api/?name={{ $reply->user->name }}&background=28a745&color=fff" class="mr-3 rounded-circle" style="width: 30px;">
                                                        <div class="media-body">
                                                            <div class="d-flex align-items-center">
                                                                <h6 class="mt-0 mb-0 mr-2">{{ $reply->user->name }} <span class="badge badge-success">Penjual</span></h6>
                                                                <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                                            </div>
                                                            <p class="text-muted small mb-0">{{ $reply->comment }}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted small text-center py-4">Belum ada diskusi produk.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <hr class="container">

            <section class="store-review">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-lg-8">
                            <h5 class="mb-4">Customer Review ({{ $product->reviews->count() }})</h5>
                            <ul class="list-unstyled">
                                @forelse($product->reviews as $review)
                                    <li class="media my-4">
                                        <img src="https://ui-avatars.com/api/?name={{ $review->user->name }}" class="mr-3 rounded-circle" style="width: 50px;" alt="{{ $review->user->name }}" />
                                        <div class="media-body">
                                            <h5 class="mt-2 mb-1">{{ $review->user->name }}</h5>
                                            {{ $review->comment }}
                                        </div>
                                    </li>
                                @empty
                                    <li class="text-center text-muted">Belum ada review untuk produk ini.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('addon-script')
    <script src="/vendor/vue/vue.js"></script>
    <script>
        var gallery = new Vue({
            el: "#gallery",
            mounted() {
                AOS.init();
            },
            data: {
                activePhoto: 0,
                photos: [
                    @foreach ($product->galleries as $gallery)
                    {
                        id: {{ $gallery->id }},
                        url: "{{ Storage::url($gallery->photos) }}",
                    },
                    @endforeach
                ],
            },
            methods: {
                changeActive(id) {
                    this.activePhoto = id;
                },
            },
        });
    </script>
@endpush