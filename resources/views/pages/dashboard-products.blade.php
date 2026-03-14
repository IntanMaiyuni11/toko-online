@extends('layouts.dashboard')

@section('title')
    Store Dashboard Products
@endsection

@section('content')
<div class="section-content section-dashboard-home" data-aos="fade-up">
    <div class="container-fluid">
        <div class="dashboard-heading">
            <h2 class="dashboard-title">My Products</h2>
            <p class="dashboard-subtitle">Manage it well and get money</p>
        </div>
        <div class="dashboard-content">
            <div class="row">
                <div class="col-12">
                    <a href="{{ route('dashboard-product-create') }}" class="btn btn-success">
                        Add New Product
                    </a>
                </div>
            </div>

            <div class="row mt-4">
              @foreach ($products as $product)
<div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <a class="card card-dashboard-product d-block" href="{{ route('dashboard-product-details', $product->id) }}">
        <div class="card-body">
            <div class="product-image-container mb-2">
                <img 
                    src="{{ $product->galleries->count() ? Storage::url($product->galleries->first()->photos) : 'https://via.placeholder.com/300x200?text=No+Image' }}" 
                    alt="{{ $product->name }}" 
                />
                {{-- BADGE STOK --}}
                @if($product->stock <= 0)
                    <span class="badge badge-danger position-absolute" style="top: 10px; right: 10px;">Habis</span>
                @elseif($product->stock <= 5)
                    <span class="badge badge-warning position-absolute" style="top: 10px; right: 10px;">Sisa {{ $product->stock }}</span>
                @else
                    <span class="badge badge-success position-absolute" style="top: 10px; right: 10px;">Stok: {{ $product->stock }}</span>
                @endif
            </div>
            <div class="product-title">{{ $product->name }}</div>
            <div class="product-category text-muted">{{ $product->category->name }}</div>
            {{-- TAMBAHAN HARGA --}}
            <div class="product-price text-success font-weight-bold">Rp {{ number_format($product->price) }}</div>
        </div>
    </a>
</div>
@endforeach
          </div>

            @if($products->count() == 0)
                <div class="row mt-5">
                    <div class="col-12 text-center">
                        <p class="text-muted">Kamu belum memiliki produk yang terdaftar.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
@push('addon-style')
<style>
    .card-dashboard-product {
        border-radius: 15px;
        overflow: hidden;
        background: #ffffff;
        border: none;
        transition: all 0.3s ease;
    }

    .product-image-container {
        width: 100%;
        height: 160px; 
        overflow: hidden;
        background-color: #f8f9fa;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-image-container img {
        width: 100%;
        height: 100%;
        object-fit: contain; 
        padding: 5px; 
    }

    .product-title {
        font-size: 16px;
        font-weight: 500;
        color: #0c0d36;
        margin-top: 10px;
    }

    .product-category {
        font-size: 14px;
        color: #979797;
    }
</style>
  
@endpush