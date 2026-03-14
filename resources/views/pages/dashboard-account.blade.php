@extends('layouts.dashboard')

@section('title')
  Account Settings
@endsection

@section('content')
<div
  class="section-content section-dashboard-home"
  data-aos="fade-up"
>
  <div class="container-fluid">
    <div class="dashboard-heading">
      <h2 class="dashboard-title">My Account</h2>
      <p class="dashboard-subtitle">
        Update your current profile
      </p>
    </div>
    <div class="dashboard-content">
      <div class="row">
        <div class="col-12">
          <form id="locations" action="{{ route('dashboard-settings-redirect','dashboard-settings-account') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
              <div class="card-body">
                <div class="row">
                  {{-- BAGIAN UPLOAD FOTO PROFIL --}}
                  <div class="col-md-12 mb-4 text-center">
                    <div class="form-group">
                      <div class="profile-photo-preview mb-3 position-relative d-inline-block">
                        <img 
                          src="{{ $user->photos ? Storage::url($user->photos) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0D8ABC&color=fff' }}" 
                          alt="Profile Preview" 
                          class="rounded-circle img-thumbnail shadow-sm"
                          id="img-preview"
                          style="width: 150px; height: 150px; object-fit: cover;"
                        >
                        {{-- Tombol Hapus Foto (Muncul jika user punya foto) --}}
                        @if($user->photos)
                          <button type="button" 
                                  class="btn btn-danger btn-sm position-absolute" 
                                  style="top: 5px; right: 5px; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border: 2px solid white;"
                                  onclick="deletePhoto()"
                                  id="btn-delete-photo"
                                  title="Hapus Foto">
                            <i class="fas fa-times"></i>
                          </button>
                        @endif
                      </div>

                      <div class="mt-2">
                        <label for="photos" class="btn btn-outline-success btn-sm">
                          <i class="fas fa-camera mr-1"></i> Ganti Foto Profil
                        </label>
                        <input 
                          type="file" 
                          name="photos" 
                          id="photos" 
                          class="d-none" 
                          accept="image/*" 
                          onchange="previewImage(this)"
                        >
                        {{-- Hidden input untuk flag hapus ke controller --}}
                        <input type="hidden" name="delete_photo" id="delete_photo" value="0">
                        
                        <small class="text-muted d-block mt-1">Format: JPG, PNG. Maksimal 2MB</small>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="name">Your Name</label>
                      <input
                        type="text"
                        class="form-control"
                        id="name"
                        name="name"
                        value="{{ $user->name }}"
                      />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="email">Your Email</label>
                      <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        value="{{ $user->email }}"
                      />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="address_one">Address 1</label>
                      <input
                        type="text"
                        class="form-control"
                        id="address_one"
                        name="address_one"
                        value="{{ $user->address_one }}"
                      />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="address_two">Address 2</label>
                      <input
                        type="text"
                        class="form-control"
                        id="address_two"
                        name="address_two"
                        value="{{ $user->address_two }}"
                      />
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="provinces_id">Province</label>
                      <select name="provinces_id" id="provinces_id" class="form-control" v-model="provinces_id" v-if="provinces">
                        <option v-for="province in provinces" :value="province.id">@{{ province.name }}</option>
                      </select>
                      <select v-else class="form-control"></select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="regencies_id">City</label>
                      <select name="regencies_id" id="regencies_id" class="form-control" v-model="regencies_id" v-if="regencies">
                        <option v-for="regency in regencies" :value="regency.id">@{{regency.name }}</option>
                      </select>
                      <select v-else class="form-control"></select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="zip_code">Postal Code</label>
                      <input
                        type="text"
                        class="form-control"
                        id="zip_code"
                        name="zip_code"
                        value="{{ $user->zip_code }}"
                      />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="country">Country</label>
                      <input
                        type="text"
                        class="form-control"
                        id="country"
                        name="country"
                        value="{{ $user->country }}"
                      />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="phone_number">Mobile</label>
                      <input
                        type="text"
                        class="form-control"
                        id="phone_number"
                        name="phone_number"
                        value="{{ $user->phone_number }}"
                      />
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col text-right">
                    <button
                      type="submit"
                      class="btn btn-success px-5"
                    >
                      Save Now
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('addon-script')
    <script src="/vendor/vue/vue.js"></script>
    <script src="https://unpkg.com/vue-toasted"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
      // 1. Fungsi Preview Foto saat memilih file
      function previewImage(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function(e) {
            document.getElementById('img-preview').src = e.target.result;
            // Reset flag hapus jika user memilih file baru setelah menekan hapus
            document.getElementById('delete_photo').value = "0";
          }
          reader.readAsDataURL(input.files[0]);
        }
      }

      // 2. Fungsi Hapus Foto (UI side)
      function deletePhoto() {
        if(confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
          // Ganti ke Avatar Default (Placeholder)
          const name = encodeURIComponent("{{ $user->name }}");
          const avatarUrl = `https://ui-avatars.com/api/?name=${name}&background=0D8ABC&color=fff`;
          
          document.getElementById('img-preview').src = avatarUrl;
          
          // Set flag hapus menjadi 1 agar controller tahu harus menghapus file
          document.getElementById('delete_photo').value = "1";
          
          // Kosongkan input file jika sebelumnya sudah memilih file
          document.getElementById('photos').value = "";

          // Sembunyikan tombol hapus sementara sebelum disave
          const btnDelete = document.getElementById('btn-delete-photo');
          if(btnDelete) btnDelete.style.display = 'none';
        }
      }

      // 3. Inisialisasi Vue untuk dropdown wilayah
      var locations = new Vue({
        el: "#locations",
        mounted() {
          this.getProvincesData();
          // Set ID provinsi & kota awal jika ada
          this.provinces_id = "{{ $user->provinces_id }}";
          this.regencies_id = "{{ $user->regencies_id }}";
        },
        data: {
          provinces: null,
          regencies: null,
          provinces_id: null,
          regencies_id: null,
        },
        methods: {
          getProvincesData() {
            var self = this;
            axios.get('{{ route('api-provinces') }}')
              .then(function (response) {
                  self.provinces = response.data;
              })
          },
          getRegenciesData() {
            var self = this;
            axios.get('{{ url('api/regencies') }}/' + self.provinces_id)
              .then(function (response) {
                  self.regencies = response.data;
              })
          },
        },
        watch: {
          provinces_id: function (val, oldVal) {
            this.regencies_id = null;
            this.getRegenciesData();
          },
        }
      });
    </script>
@endpush