<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>@yield('title')</title>
    <link rel="icon" href="{{ asset('images/Logo.png') }}?v=2.2" type="image/png" sizes="32x32">

    {{-- Script Midtrans Snap --}}
    <script 
        src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ config('services.midtrans.clientKey') }}">
    </script>

     {{-- style --}}
     @stack('prepend-style')
     @include('includes.style')
     @stack('addon-style')

  </head>

  <body>
        {{-- Navbar --}}
        @include('includes.navbar')


   {{-- page Content  --}}
@yield('content')
  
{{-- Footer --}}
@include('includes.footer')

{{-- script --}}
 @stack('prepend-script')
     @include('includes.script')
     @stack('addon-script')
  </body>
</html>
