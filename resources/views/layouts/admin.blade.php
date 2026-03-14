<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="BWAStore Admin Panel" />
    <meta name="author" content="Admin" />

    <title>@yield('title') - Admin Panel</title>
    
    <link rel="icon" href="{{ asset('images/Logo.png') }}?v=2.2" type="image/png" sizes="32x32">

    @stack('prepend-style')
    {{-- Core CSS --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <link href="/style/main.css" rel="stylesheet" />
    {{-- Font Awesome untuk Ikon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    {{-- DataTables --}}
    <link href="https://cdn.datatables.net/v/bs4/dt-2.3.7/datatables.min.css" rel="stylesheet">

    <style>
        /* Custom Sidebar Styling */
        #sidebar-wrapper {
            min-height: 100vh;
            background-color: #ffffff;
            transition: all 0.3s ease;
            box-shadow: 2px 0 15px rgba(0,0,0,0.05);
            z-index: 1000;
        }

        #sidebar-wrapper .sidebar-heading {
            padding: 1.5rem 1.25rem;
        }

        #sidebar-wrapper .list-group-item {
            padding: 12px 25px;
            border: none;
            font-size: 15px;
            color: #7d879c;
            transition: all 0.2s ease-in-out;
            background: transparent;
        }

        #sidebar-wrapper .list-group-item i {
            width: 25px;
            font-size: 17px;
            text-align: center;
        }

        /* Hover Effect */
        #sidebar-wrapper .list-group-item:hover {
            color: #2979ff;
            background-color: #f8f9fa;
            padding-left: 30px;
        }

        /* Active State */
        #sidebar-wrapper .list-group-item.active {
            background-color: #f1f6ff !important;
            color: #2979ff !important;
            font-weight: 600;
            border-right: 4px solid #2979ff;
        }

        /* Separator Text */
        .sidebar-section-title {
            font-size: 11px;
            font-weight: 700;
            color: #abb3c4;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 25px 25px 10px;
        }

        /* Profile Picture in Navbar */
        .profile-picture {
            width: 35px;
            height: 35px;
            object-fit: cover;
        }

        .navbar-store {
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }

        /* Toggle Button */
        #menu-toggle {
            background-color: #2979ff;
            border: none;
            border-radius: 8px;
        }
    </style>
    @stack('addon-style')
  </head>

  <body>
    <div class="page-dashboard">
      <div class="d-flex" id="wrapper" data-aos="fade-right">
        <div class="border-right" id="sidebar-wrapper">
          <div class="sidebar-heading text-center">
            <img src="/images/admin.png" alt="Admin Logo" class="my-3" style="max-width: 110px;" />
          </div>
          
          <div class="list-group list-group-flush">
            <a href="{{ route('admin.dashboard') }}" 
               class="list-group-item list-group-item-action d-flex align-items-center {{ (request()->is('admin/dashboard')) ? 'active' : '' }}">
               <i class="fas fa-th-large mr-3"></i> Dashboard
            </a>

            <div class="sidebar-section-title">Store Management</div>
            
            <a href="{{ route('admin.product.index') }}" 
               class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('admin.product.*') ? 'active' : '' }}">
               <i class="fas fa-box mr-3"></i> Products
            </a>
            
            <a href="{{ route('admin.productgallery.index') }}" 
               class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('admin.productgallery.*') ? 'active' : '' }}">
               <i class="fas fa-image mr-3"></i> Galleries
            </a>
            
            <a href="{{ route('admin.category.index') }}" 
               class="list-group-item list-group-item-action d-flex align-items-center {{ (request()->is('admin/category*')) ? 'active' : '' }}">
               <i class="fas fa-layer-group mr-3"></i> Categories
            </a>
            
            <a href="{{ route('admin.transaction.index') }}" 
               class="list-group-item list-group-item-action d-flex align-items-center {{ (request()->is('admin/transaction*')) ? 'active' : '' }}">
               <i class="fas fa-shopping-cart mr-3"></i> Transactions
            </a>

            <div class="sidebar-section-title">Administrative</div>
            
            <a href="{{ route('admin.user.index') }}" 
               class="list-group-item list-group-item-action d-flex align-items-center {{ (request()->is('admin/user*')) ? 'active' : '' }}">
               <i class="fas fa-user-shield mr-3"></i> Users
            </a>
            
            <a href="{{ route('admin.rewards.index') }}" 
               class="list-group-item list-group-item-action d-flex align-items-center {{ (request()->is('admin/reward*')) ? 'active' : '' }}">
               <i class="fas fa-award mr-3"></i> Rewards
            </a>

            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="list-group-item list-group-item-action d-flex align-items-center text-danger mt-3">
               <i class="fas fa-power-off mr-3"></i> Sign Out
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
          </div>
        </div>

        <div id="page-content-wrapper">
          <nav class="navbar navbar-expand-lg navbar-light navbar-store fixed-top" data-aos="fade-down">
            <div class="container-fluid">
              <button class="btn btn-primary d-md-none mr-auto mr-2" id="menu-toggle">
                <i class="fas fa-bars"></i>
              </button>
              
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
              </button>

              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav d-none d-lg-flex ml-auto align-items-center">
                  <li class="nav-item dropdown">
                    <a href="#" class="nav-link d-flex align-items-center" id="navbarDropdown" role="button" data-toggle="dropdown">
                      <img src="{{ Auth::user()->photos ? Storage::url(Auth::user()->photos) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=0D8ABC&color=fff' }}" 
                          alt="" 
                          class="rounded-circle mr-2 profile-picture border" 
                          style="width: 45px; height: 45px; object-fit: cover;" />
                      <div class="d-flex flex-column">
                          <span class="font-weight-bold" style="line-height: 1;">{{ Auth::user()->name }}</span>
                          <small class="text-muted">{{ Auth::user()->roles }}</small>
                      </div>
                  </a>
                    <div class="dropdown-menu dropdown-menu-right shadow-sm border-0">
                      <a href="/" class="dropdown-item">View Website</a>
                      <div class="dropdown-divider"></div>
                      <a href="{{ route('logout') }}" 
                         onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                         class="dropdown-item text-danger">Logout</a>
                    </div>
                  </li>
                </ul>

                <ul class="navbar-nav d-block d-lg-none mt-3">
                  <li class="nav-item">
                    <a href="#" class="nav-link font-weight-bold">Hi, Admin</a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link text-danger">Logout</a>
                  </li>
                </ul>
              </div>
            </div>
          </nav>

          {{-- Konten Utama --}}
          <div class="py-4">
              @yield('content')
          </div>

        </div>
      </div>
    </div>

    @stack('prepend-script')
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs4/dt-2.3.7/datatables.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
      AOS.init();
      
      // Menu Toggle Logic
      $("#menu-toggle").click(function (e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
      });

      // Default DataTable Initialization
      $(document).ready(function() {
        if($('#datatable').length > 0) {
            $('#datatable').DataTable();
        }
      });
    </script>
    @stack('addon-script')
  </body>
</html>