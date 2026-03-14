@extends('layouts.app')

@section('title')
    Store Cart Page
@endsection

@push('addon-style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    :root {
        --primary-color: #29a704;
        --secondary-color: #8D1C12;
        --bg-light: #f4f7f6;
    }

    .page-cart { background-color: var(--bg-light); min-height: 100vh; padding-bottom: 50px; }
    
    /* Breadcrumbs styling */
    .breadcrumb { background: transparent; padding: 0; margin-bottom: 30px; }
    .breadcrumb-item a { color: #999; text-decoration: none; }
    
    /* Card Styling */
    .cart-card { 
        background: #fff; 
        border-radius: 15px; 
        border: none; 
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        margin-bottom: 20px;
        transition: transform 0.2s;
        padding: 1.5rem !important;
    }
    .cart-card:hover { transform: translateY(-3px); }

    .cart-image { 
        width: 100%; 
        max-width: 100px; 
        height: 80px; 
        object-fit: cover; 
        border-radius: 12px; 
    }

    /* Qty Control */
    .qty-control { 
        display: flex; 
        align-items: center; 
        background: #f1f1f1; 
        border-radius: 10px; 
        padding: 5px;
        width: fit-content;
    }
    .btn-qty { 
        width: 28px; height: 28px; 
        border-radius: 8px; border: none; 
        background: #fff; color: var(--secondary-color); 
        display: flex; align-items: center; justify-content: center; 
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        cursor: pointer;
    }
    .input-qty { 
        width: 35px; border: none; background: transparent; 
        text-align: center; font-weight: bold; font-size: 0.9rem;
    }

    /* Price Styling */
    .product-price {
        font-weight: 700;
        color: #333;
        white-space: nowrap; /* Mencegah harga pecah ke baris baru */
    }

    /* Form Styling */
    .section-title { font-weight: 700; color: #333; margin-bottom: 25px; position: relative; padding-left: 15px; }
    .section-title::before { 
        content: ''; position: absolute; left: 0; top: 5px; 
        height: 20px; width: 4px; background: var(--primary-color); border-radius: 10px; 
    }
    
    .form-control { 
        border-radius: 10px; border: 1px solid #eee; padding: 12px 15px; height: auto;
        background: #fff; transition: all 0.3s;
    }
    .form-control:focus { box-shadow: 0 0 0 3px rgba(41, 167, 4, 0.1); border-color: var(--primary-color); }

    /* Summary Sidebar */
    .summary-box { 
        background: #fff; padding: 30px; border-radius: 20px; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        position: sticky; top: 20px;
    }
    .summary-item { display: flex; justify-content: space-between; margin-bottom: 15px; color: #777; }
    .summary-total { 
        display: flex; justify-content: space-between; 
        margin-top: 20px; padding-top: 20px; 
        border-top: 2px dashed #eee; 
        font-size: 1.2rem; font-weight: 800; color: #333;
    }

    .btn-checkout { 
        background: var(--primary-color); color: #fff; 
        border: none; padding: 15px; border-radius: 12px; 
        font-weight: 700; font-size: 1.1rem; width: 100%;
        transition: all 0.3s; margin-top: 20px;
    }
    .btn-checkout:hover { background: #218803; transform: scale(1.02); color: #fff; }
    
    .btn-delete { color: #ff4d4d; border: none; background: none; font-size: 1.1rem; transition: 0.3s; cursor: pointer; }
    .btn-delete:hover { color: #cc0000; transform: rotate(10deg); }

    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

    @media (max-width: 768px) {
        .cart-card { text-align: center; }
        .qty-control { margin: 15px auto !important; }
        .product-price { text-align: center !important; margin-bottom: 10px; }
    }
</style>
@endpush

@section('content')
<div class="page-content page-cart" id="locations">
    <section class="store-breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Cart</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <section class="store-cart">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h2 class="section-title text-black">Shopping Cart</h2>
                    @foreach ($carts as $index => $cart)
                    <div class="card cart-card">
                        <div class="row align-items-center">
                            <div class="col-4 col-md-2">
                                @if($cart->product->galleries->count())
                                    <img src="{{ Storage::url($cart->product->galleries->first()->photos) }}" class="cart-image img-fluid" />
                                @else
                                    <div class="cart-image bg-light d-flex align-items-center justify-content-center text-muted small mx-auto">No Image</div>
                                @endif
                            </div>
                            
                            <div class="col-8 col-md-3">
                                <h5 class="mb-0 font-weight-bold text-black" style="font-size: 1rem;">{{ $cart->product->name }}</h5>
                                <p class="text-muted small mb-0">Seller: <span class="text-success">{{ $cart->product->user->store_name }}</span></p>
                            </div>

                            <div class="col-6 col-md-3 mt-3 mt-md-0">
                                <div class="qty-control">
                                    <button class="btn-qty" type="button" @click="minusQuantity({{ $index }})"><i class="fas fa-minus fa-xs"></i></button>
                                    <input type="number" class="input-qty" v-model="cartItems[{{ $index }}].quantity" readonly>
                                    <button class="btn-qty" type="button" @click="plusQuantity({{ $index }})"><i class="fas fa-plus fa-xs"></i></button>
                                </div>
                            </div>

                            <div class="col-4 col-md-3 mt-3 mt-md-0 text-md-right">
                                <div class="product-price h6 mb-0">Rp {{ number_format($cart->product->price, 0, ',', '.') }}</div>
                            </div>

                            <div class="col-2 col-md-1 mt-3 mt-md-0 text-right">
                                <form action="{{ route('cart-delete', $cart->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <form action="{{ route('checkout') }}" method="POST" id="checkout-form" class="mt-5">
                        @csrf
                        <template v-for="(item, index) in cartItems">
                            <input type="hidden" name="cart_ids[]" :value="item.id">
                            <input type="hidden" name="quantities[]" :value="item.quantity">
                        </template>

                        <h2 class="section-title text-black">Shipping Details</h2>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small font-weight-bold text-uppercase">Address 1</label>
                                <input type="text" class="form-control" name="address_one" value="Setra Duta Cemara" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small font-weight-bold text-uppercase">Address 2</label>
                                <input type="text" class="form-control" name="address_two" value="Blok B2 No. 34" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-muted small font-weight-bold text-uppercase">Province</label>
                                <select name="provinces_id" class="form-control" v-model="provinces_id" v-if="provinces">
                                    <option v-for="province in provinces" :value="province.id">@{{ province.name }}</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-muted small font-weight-bold text-uppercase">City</label>
                                <select name="regencies_id" class="form-control" v-model="regencies_id" v-if="regencies">
                                    <option v-for="regency in regencies" :value="regency.id">@{{ regency.name }}</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-muted small font-weight-bold text-uppercase">Postal Code</label>
                                <input type="text" class="form-control" name="zip_code" value="40512" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small font-weight-bold text-uppercase">Mobile Number</label>
                                <input type="text" class="form-control" name="phone_number" value="+628 2020 11111" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small font-weight-bold text-uppercase">Promo Code</label>
                                <input type="text" name="voucher_code" v-model="voucher_code" class="form-control" placeholder="ABC-123">
                            </div>
                        </div>
                        <input type="hidden" name="total_price" :value="grandTotal">
                    </form>
                </div>

                <div class="col-lg-4 mt-5 mt-lg-0">
                    <div class="summary-box">
                        <h4 class="mb-4 font-weight-bold text-black">Order Summary</h4>
                        <div class="summary-item">
                            <span>Subtotal</span>
                            <span class="text-dark font-weight-bold">Rp @{{ basePrice.toLocaleString('id-ID') }}</span>
                        </div>
                        <div class="summary-item">
                            <span>Tax (10%)</span>
                            <span class="text-dark font-weight-bold">Rp @{{ totalTax.toLocaleString('id-ID') }}</span>
                        </div>
                        <div class="summary-item">
                            <span>Insurance</span>
                            <span class="text-dark font-weight-bold">Rp @{{ insurancePrice.toLocaleString('id-ID') }}</span>
                        </div>
                        <div class="summary-item">
                            <span>Shipping</span>
                            <span class="text-success font-weight-bold">FREE</span>
                        </div>
                        
                        <div class="summary-total">
                            <span>Total</span>
                            <span class="text-success">Rp @{{ grandTotal.toLocaleString('id-ID') }}</span>
                        </div>

                        <button type="submit" form="checkout-form" id="checkout-button" class="btn btn-checkout shadow-lg">
                            Checkout Now <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('addon-script')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.clientKey') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
      window.onload = function () {
          var locations = new Vue({
            el: "#locations",
            mounted() { this.getProvincesData(); },
            data: {
              provinces: null, regencies: null, provinces_id: null, regencies_id: null,
              destination: "Indonesia", insurancePrice: 100, voucher_code: "",
              cartItems: [
                @foreach($carts as $cart)
                { id: {{ $cart->id }}, price: {{ $cart->product->price }}, quantity: 1 },
                @endforeach
              ]
            },
            computed: {
              basePrice() { return this.cartItems.reduce((total, item) => total + (item.price * item.quantity), 0); },
              totalTax() { return this.basePrice * 0.1; },
              grandTotal() { return this.basePrice + this.totalTax + this.insurancePrice; }
            },
            methods: {
              plusQuantity(index) { this.cartItems[index].quantity++; },
              minusQuantity(index) { if(this.cartItems[index].quantity > 1) this.cartItems[index].quantity--; },
              getProvincesData() {
                var self = this;
                axios.get('/api/provinces').then(function (response) { self.provinces = response.data; })
              },
              getRegenciesData() {
                var self = this;
                axios.get('/api/regencies/' + self.provinces_id).then(function (response) { self.regencies = response.data; });
              },
              buyNow(event) {
                event.preventDefault(); 
                const btn = document.getElementById('checkout-button');
                btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                
                const formData = new FormData(document.getElementById('checkout-form'));
                axios.post("{{ route('checkout') }}", formData)
                  .then(function(response) {
                    window.snap.pay(response.data.snap_token, {
                      onSuccess: (result) => window.location.href = "{{ route('success') }}",
                      onPending: (result) => window.location.href = "/dashboard/transactions",
                      onError: (result) => { alert("Gagal!"); btn.disabled = false; },
                      onClose: () => { 
                        btn.disabled = false; 
                        btn.innerHTML = 'Checkout Now <i class="fas fa-arrow-right ml-2"></i>'; 
                      }
                    });
                  })
                  .catch(function(error) { 
                      btn.disabled = false; 
                      btn.innerHTML = 'Checkout Now <i class="fas fa-arrow-right ml-2"></i>';
                      alert("Terjadi kesalahan, periksa koneksi atau stok barang.");
                  });
              }
            },
            watch: {
              provinces_id: function (val) { this.getRegenciesData(); }
            }
          });
          document.getElementById('checkout-form').onsubmit = function(e) { locations.buyNow(e); };
      };
    </script>
@endpush