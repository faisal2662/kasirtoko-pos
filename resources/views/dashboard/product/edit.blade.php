@extends('dashboard.layouts.main')

@section('container')
    <h3>Data Umum</h3>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('product') }}">Barang</a></li>
            <li class="breadcrumb-item active">Tambah - Barang</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <section class="section">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('product') }}" class="btn btn-secondary"
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

                            <form action="{{ route('product.update', $product->id) }}" method="POST">
                                @csrf
                                @method('put')
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Produk</label>
                                    <input type="text" name="name" id="nama" required
                                        value="{{ $product->name }}" class="form-control" autofocus>
                                </div>

                                <div class="mb-3">
                                    <label for="price" class="form-label">Harga</label>
                                    <input type="text" name="unit_price" id="price" value="{{ $product->price }}"
                                        class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label for="stock" class="form-label">Stok</label>
                                    <input type="number" class="form-control" value="{{ $product->stock }}" name="stock"
                                        id="stock">
                                </div>
                                <div class="mb-3">
                                    <label for="unit" class="form-label">Satuan</label>
                                    <select name="unit" id="unit" class="form-control" required>

                                        <option disabled>== Pilih ==</option>
                                        @foreach ($units as $item)
                                            <option value="{{ $item->slug }}" {{ $product->unit == $item->slug ? 'selected' : '' }} > {{ $item->short }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="category" class="form-label">Kategori</label>
                                    <select name="category_id" id="category" class="form-control" required>
                                        {{-- <option value="{{ $item->category_id }}" selected>{{ $item->category_id }}
                                        </option> --}}
                                        <option disabled>== Pilih ==</option>
                                        @foreach ($categories as $item)
                                            <option value="{{ $item->id }}" {{ $product->category_id == $item->id ? 'selected' : '' }} >{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
    @section('script')
     <script src="{{ asset('js/jquery.number.min.js') }}"></script>

        <script>

            $('input#price').number(true,0)

        </script>
    @endsection
@endsection

