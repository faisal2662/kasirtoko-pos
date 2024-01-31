@extends('dashboard.layouts.main')

@section('container')
    <h3>Data Umum</h3>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item">Barang</li>
            <li class="breadcrumb-item active">Tambah - Barang</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <section class="section">
                <div class="card">
                    <div class="card-body">
                        <a href="/dashboard/product" class="btn btn-secondary"
                            style="float: right; margin-top:30px;">Kembali</a>
                        <div class="card-title">Data Barang</div>
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="/dashboard/product-add" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Produk</label>
                                <input type="text" name="name_product" id="nama" required class="form-control"
                                    autofocus>
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">Kategori</label>
                                <select name="category_id" id="category" class="form-control" required>
                                    <option selected disabled>== Pilih ==</option>
                                    @foreach ($category as $item)
                                        <option value="{{ $item->slug }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Harga</label>
                                <input type="number" name="unit_price" id="price" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="unit" class="form-label">Satuan</label>
                                <select name="unit" id="unit" class="form-control" required>
                                    <option selected disabled>== Pilih ==</option>
                                    @foreach ($units as $item)
                                        <option value="{{ $item->slug }}">{{ $item->short }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="stock" class="form-label">Stok</label>
                                <input type="number" class="form-control" name="stock" id="stock">
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
