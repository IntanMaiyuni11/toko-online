@extends('layouts.admin')

@section('title', 'Edit Reward')

@section('content')
<div class="section-content section-dashboard-home" data-aos="fade-up">
    <div class="container-fluid">
        <div class="dashboard-heading">
            <h2 class="dashboard-title">Reward</h2>
            <p class="dashboard-subtitle">Edit Reward: {{ $item->name }}</p>
        </div>
        <div class="dashboard-content">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('admin.rewards.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Nama Reward</label>
                                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $item->name) }}" required>
                                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Poin yang Dibutuhkan</label>
                                            <input type="number" name="points" class="form-control @error('points') is-invalid @enderror" value="{{ old('points', $item->points) }}" required>
                                            @error('points') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nominal Diskon (%) atau Potongan Harga</label>
                                            <input type="number" name="discount_amount" class="form-control @error('discount_amount') is-invalid @enderror" value="{{ old('discount_amount', $item->discount_amount) }}" required>
                                            @error('discount_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col text-right">
                                        <a href="{{ route('admin.rewards.index') }}" class="btn btn-secondary mr-2">Kembali</a>
                                        <button type="submit" class="btn btn-primary px-5">Update Reward</button>
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