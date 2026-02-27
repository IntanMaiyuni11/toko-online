@extends('layouts.app')

@section('title')
    Store Cart Page
@endsection

@section('content')
    <div class="page-content page-cart" id="locations">
      <section class="store-breadcrumbs" data-aos="fade-down" data-aos-delay="100">
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
          <div class="row" data-aos="fade-up" data-aos-delay="100">
            <div class="col-12 table-responsive">
              <table class="table table-borderless table-cart">
                <thead>
                  <tr>
                    <td>Image</td>
                    <td>Name &amp; Seller</td>
                    <td>Price</td>
                    <td>Menu</td>
                  </tr>
                </thead>
                <tbody>
                  @php $totalPrice = 0 @endphp
                  @foreach ($carts as $cart)
                    <tr>
                      <td style="width: 20%;">
                        @if($cart->product->galleries->count())
                          <img src="{{ Storage::url($cart->product->galleries->first()->photos) }}" class="cart-image" />
                        @endif
                      </td>
                      <td style="width: 35%;">
                        <div class="product-title">{{ $cart->product->name }}</div>
                        <div class="product-subtitle">by {{ $cart->product->user->store_name }}</div>
                      </td>
                      <td style="width: 35%;">
                        <div class="product-title">${{ number_format($cart->product->price) }}</div>
                        <div class="product-subtitle">USD</div>
                      </td>
                      <td style="width: 20%;">
                        <form action="{{ route('cart-delete', $cart->id) }}" method="POST">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-remove-cart">Remove</button>
                        </form>
                      </td>
                    </tr>
                    @php $totalPrice += $cart->product->price @endphp
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="row" data-aos="fade-up" data-aos-delay="150">
            <div class="col-12"><hr /></div>
            <div class="col-12"><h2 class="mb-4">Shipping Details</h2></div>
          </div>
          
          <form action="{{ route('checkout') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="total_price" value="{{ $totalPrice }}">
            
            <div class="row mb-2" data-aos="fade-up" data-aos-delay="200">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Address 1</label>
                  <input type="text" class="form-control" name="address_one" value="Setra Duta Cemara" />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Address 2</label>
                  <input type="text" class="form-control" name="address_two" value="Blok B2 No. 34" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Province</label>
                  <select name="provinces_id" class="form-control" v-model="provinces_id" v-if="provinces">
                    <option v-for="province in provinces" :value="province.id">@{{ province.name }}</option>
                  </select>
                  <select v-else class="form-control"></select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>City</label>
                  <select name="regencies_id" class="form-control" v-model="regencies_id" v-if="regencies">
                    <option v-for="regency in regencies" :value="regency.id">@{{ regency.name }}</option>
                  </select>
                  <select v-else class="form-control"></select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Postal Code</label>
                  <input type="text" class="form-control" name="zip_code" value="40512" />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Country</label>
                  <input type="text" class="form-control" name="country" value="Indonesia" />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Mobile</label>
                  <input type="text" class="form-control" name="phone_number" value="+628 2020 11111" />
                </div>
              </div>
            </div>

            <div class="row" data-aos="fade-up" data-aos-delay="150">
              <div class="col-12"><hr /></div>
              <div class="col-12"><h2 class="mb-1">Payment Informations</h2></div>
            </div>

            <input type="hidden" name="total_price" :value="grandTotal">

            <div class="row" data-aos="fade-up" data-aos-delay="200">
              <div class="col-4 col-md-2">
                <div class="product-title">$@{{ totalTax.toLocaleString() }}</div>
                <div class="product-subtitle">Country Tax</div>
              </div>
              <div class="col-4 col-md-3">
                <div class="product-title">$@{{ insurancePrice.toLocaleString() }}</div>
                <div class="product-subtitle">Product Insurance</div>
              </div>
              <div class="col-4 col-md-2">
                <div class="product-title">@{{ destination }}</div>
                <div class="product-subtitle">Ship to</div>
              </div>
              <div class="col-4 col-md-2">
                <div class="product-title text-success">$@{{ grandTotal.toLocaleString() }}</div>
                <div class="product-subtitle">Total</div>
              </div>
              <div class="col-8 col-md-3">
                <button type="submit" class="btn btn-success mt-4 px-4 btn-block">Checkout Now</button>
              </div>
            </div>
          </form>
        </div>
      </section>
    </div>
@endsection

@push('addon-script')
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    
    <script>
      window.onload = function () {
          var locations = new Vue({
            el: "#locations",
            mounted() {
              this.getProvincesData();
            },
            data: {
              provinces: null,
              regencies: null,
              provinces_id: null,
              regencies_id: null,
              destination: "Indonesia",
              // Ambil total harga produk dari PHP
              basePrice: {{ $totalPrice ?? 0 }},
              insurancePrice: 5 // Biaya asuransi tetap
            },
            computed: {
              totalTax() {
                // Pajak 10% dari harga produk
                return this.basePrice * 0.1;
              },
              grandTotal() {
                // Total yang harus dibayar
                return this.basePrice + this.totalTax + this.insurancePrice;
              }
            },
            methods: {
              getProvincesData() {
                var self = this;
                axios.get('/api/provinces')
                  .then(function (response) {
                      self.provinces = response.data;
                  })
              },
              getRegenciesData() {
                var self = this;
                axios.get('/api/regencies/' + self.provinces_id)
                  .then(function (response) {
                      self.regencies = response.data;
                  });
              },
            },
            watch: {
              provinces_id: function (val) {
                this.regencies_id = null;
                this.getRegenciesData();
                var self = this;
                var province = self.provinces.find(p => p.id == val);
                if(province) {
                    self.destination = province.name;
                }
              },
            }
          });
      };
    </script>
@endpush