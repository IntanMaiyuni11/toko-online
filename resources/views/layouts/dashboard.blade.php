<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>@yield('title')</title>
    
    <link rel="icon" href="{{ asset('images/Logo.png') }}?v=2.2" type="image/png" sizes="32x32">

    @stack('prepend-style')
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <link href="/style/main.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
      /* Tambahan styling agar sidebar lebih rapi */
      #sidebar-wrapper .list-group-item {
        border: none;
        padding: 10px 25px;
        transition: all 0.3s;
      }
      #sidebar-wrapper .list-group-item.active {
        background-color: #f8f9fa;
        color: #29a867;
        font-weight: bold;
        border-right: 4px solid #29a867;
      }
      .profile-picture {
        border: 2px solid #ddd;
      }
      .dropdown-divider {
        margin: 0.5rem 1.2rem;
      }
    </style>
    @stack('addon-style')
  </head>

  <body>
    <div class="page-dashboard">
      <div class="d-flex" id="wrapper" data-aos="fade-right">
        <div class="border-right" id="sidebar-wrapper">
          <div class="sidebar-heading text-center">
            <img src="/images/dashboard-store-logo.svg" alt="Logo" class="my-4" />
          </div>

          <div class="list-group list-group-flush">
            <a href="{{ route('dashboard') }}"
              class="list-group-item list-group-item-action {{ (request()->is('dashboard')) ? 'active' : '' }}">
              <i class="fa fa-tachometer-alt mr-2"></i> Dashboard
            </a>

            {{-- 1. MENU KHUSUS SELLER / ADMIN --}}
            @if(Auth::user()->roles == 'USER' || Auth::user()->roles == 'ADMIN')
                <div class="dropdown-divider"></div>
                <small class="text-muted ml-4 my-2 d-block text-uppercase" style="letter-spacing: 1px; font-size: 10px;">Aktivitas Toko</small>

                <a href="{{ route('dashboard-product') }}"
                  class="list-group-item list-group-item-action {{ (request()->is('dashboard/products*')) ? 'active' : '' }}">
                  <i class="fa fa-box mr-2"></i> My Products
                </a>

                <a href="{{ route('dashboard-orders') }}"
                  class="list-group-item list-group-item-action {{ (request()->routeIs('dashboard-orders*')) ? 'active' : '' }}">
                  <i class="fa fa-shopping-cart mr-2"></i> Pesanan Masuk
                </a>

                <a href="{{ route('dashboard-logistics') }}"
                  class="list-group-item list-group-item-action {{ (request()->is('dashboard/logistics*')) ? 'active' : '' }}">
                  <i class="fa fa-truck mr-2"></i> Pengiriman
                </a>

                <a href="{{ route('dashboard-statistics') }}"
                  class="list-group-item list-group-item-action {{ (request()->is('dashboard/statistics*')) ? 'active' : '' }}">
                  <i class="fa fa-chart-bar mr-2"></i> Statistik
                </a>

                <a href="{{ route('dashboard-performance') }}"
                  class="list-group-item list-group-item-action {{ (request()->is('dashboard/performance*')) ? 'active' : '' }}">
                  <i class="fa fa-chart-line mr-2"></i> Performa
                </a>
            @endif

            {{-- 2. MENU AKTIVITAS BELANJA --}}
            <div class="dropdown-divider"></div>
            <small class="text-muted ml-4 my-2 d-block text-uppercase" style="letter-spacing: 1px; font-size: 10px;">Aktivitas Belanja</small>

            <a href="{{ route('dashboard-my-orders') }}"
              class="list-group-item list-group-item-action {{ (request()->routeIs('dashboard-my-orders*')) ? 'active' : '' }}">
              <i class="fa fa-shopping-bag mr-2"></i> Pesanan Saya
            </a>

            <a href="{{ route('dashboard-transaction') }}"
              class="list-group-item list-group-item-action {{ (request()->is('dashboard/transactions*')) ? 'active' : '' }}">
              <i class="fa fa-history mr-2"></i> Riwayat Transaksi
            </a>

            <a href="{{ route('dashboard-reviews') }}"
              class="list-group-item list-group-item-action {{ (request()->is('dashboard/reviews*')) ? 'active' : '' }}">
              <i class="fa fa-star mr-2"></i> Ulasan
            </a>
        
            {{-- 3. MENU PROMO & POIN --}}
            <div class="dropdown-divider"></div>
            <small class="text-muted ml-4 my-2 d-block text-uppercase" style="letter-spacing: 1px; font-size: 10px;">Promo & Poin</small>
            <a href="{{ route('dashboard-promotions') }}"
              class="list-group-item list-group-item-action {{ (request()->is('dashboard/promotions*')) ? 'active' : '' }}">
              <i class="fa fa-gift mr-2"></i> My Rewards
            </a>

            {{-- 4. MENU KOMUNIKASI --}}
            <div class="dropdown-divider"></div>
            <small class="text-muted ml-4 my-2 d-block text-uppercase" style="letter-spacing: 1px; font-size: 10px;">Komunikasi</small>
            <a href="{{ route('dashboard-chat') }}"
                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ (request()->is('dashboard/chat*')) ? 'active' : '' }}">
                <span><i class="fa fa-comments mr-2"></i> Chat</span>
                @php
                    $unreadCount = \App\Models\Message::where('receiver_id', Auth::id())->where('is_read', 0)->count();
                @endphp
                @if($unreadCount > 0)
                    <span class="badge badge-pill badge-danger">{{ $unreadCount }}</span>
                @endif
            </a>

            {{-- 5. MENU BANTUAN --}}
            <div class="dropdown-divider"></div>
            <small class="text-muted ml-4 my-2 d-block text-uppercase" style="letter-spacing: 1px; font-size: 10px;">Bantuan</small>
            <a href="{{ route('dashboard-education') }}"
              class="list-group-item list-group-item-action {{ (request()->routeIs('dashboard-education')) ? 'active' : '' }}">
              <i class="fa fa-book-open mr-2"></i> Pusat Edukasi
            </a>

            {{-- 6. PENGATURAN --}}
            <div class="dropdown-divider"></div>
            <small class="text-muted ml-4 my-2 d-block text-uppercase" style="letter-spacing: 1px; font-size: 10px;">Pengaturan</small>

            @if(Auth::user()->roles == 'USER' || Auth::user()->roles == 'ADMIN')
                <a href="{{ route('dashboard-settings-store') }}"
                  class="list-group-item list-group-item-action {{ (request()->is('dashboard/settings*')) ? 'active' : '' }}">
                  <i class="fa fa-store mr-2"></i> Store Settings
                </a>
            @endif

            <a href="{{ route('dashboard-settings-account') }}"
              class="list-group-item list-group-item-action {{ (request()->is('dashboard/account*')) ? 'active' : '' }}">
              <i class="fa fa-user-circle mr-2"></i> My Account
            </a>

            <a href="{{ route('logout') }}"
              onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
              class="list-group-item list-group-item-action text-danger mt-4 mb-5">
              <i class="fa fa-sign-out-alt mr-2"></i> Sign Out
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
          </div>
        </div>

        <div id="page-content-wrapper">
          <nav class="navbar navbar-expand-lg navbar-light navbar-store fixed-top" data-aos="fade-down">
            <div class="container-fluid">
              <button class="btn btn-secondary d-md-none mr-auto mr-2" id="menu-toggle">
                &laquo; Menu
              </button>
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
              </button>
              
              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav d-none d-lg-flex ml-auto">
                  <li class="nav-item dropdown">
                    <a href="#" class="nav-link" id="navbarDropdown" role="button" data-toggle="dropdown">
                        <img src="{{ Auth::user()->photos ? Storage::url(Auth::user()->photos) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=0D8ABC&color=fff' }}" 
                        alt="Profile" 
                        class="rounded-circle mr-2 profile-picture" 
                        style="width: 45px; height: 45px; object-fit: cover;" />
                        Hi, {{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="{{ route('home') }}" class="dropdown-item">Home</a>
                        <a href="{{ route('dashboard-settings-account') }}" class="dropdown-item">Settings</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                           Logout
                        </a>
                    </div>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('cart') }}" class="nav-link d-inline-block mt-2">
                        @php
                            $carts = \App\Models\Cart::where('users_id', Auth::user()->id)->count();
                        @endphp
                        <div class="cart-badge-container" style="position: relative; display: inline-block;">
                            @if($carts > 0)
                                <img src="/images/icon-cart-filled.svg" alt="Cart" />
                                <div class="card-badge" style="
                                    position: absolute;
                                    top: -8px;
                                    right: -10px;
                                    background-color: #29a867;
                                    color: white;
                                    border-radius: 50%;
                                    padding: 1px 6px;
                                    font-size: 11px;
                                    font-weight: bold;
                                    border: 2px solid white;
                                ">
                                    {{ $carts }}
                                </div>
                            @else
                                <img src="/images/icon-cart-empty.svg" alt="Cart" />
                            @endif
                        </div>
                    </a>
                  </li>
                </ul>

                {{-- Mobile Menu --}}
                <ul class="navbar-nav d-block d-lg-none">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link font-weight-bold">Hi, {{ Auth::user()->name }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('cart') }}" class="nav-link">Cart ({{ $carts }})</a>
                    </li>
                </ul>    
              </div>
            </div>
          </nav>

          {{-- Content --}}
          @yield('content')

        </div>
      </div>
    </div>

    @stack('prepend-script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
      AOS.init();
      $("#menu-toggle").click(function (e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
      });
    </script>
    @stack('addon-script')
  </body>
</html>