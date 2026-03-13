@extends('dashboard.layouts.main')

@section('title')
    Kasir | Tambah Barang
@endsection
@section('container')
    <h3>Tambah Barang</h3>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item"> <a href="{{ route('product') }}">Barang </a> </li>
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
                        <form action="{{ route('product.store') }}" id="formProduct" enctype="multipart/form-data"
                            method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Produk <span class="text-danger">*</span>
                                </label>
                                <input type="text" autofocus value="" name="name" id="nama" required
                                    class="form-control" autofocus>
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select name="category_id" id="category" class="form-control" required>
                                    <option selected  disabled>-- Pilih Kategori -- </option>
                                    @foreach ($category as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="unit" class="form-label">Satuan Dasar <span class="text-danger">*</span>
                                </label>
                                <select name="unit" id="unit" class="form-control" required>
                                    <option selected disabled>-- Pilih Dasar --</option>
                                    @foreach ($units as $item)
                                        <option value="{{ $item->id }}">{{ $item->short }} - {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Satuan Pembelian <span
                                        class="text-danger">*</span></label>
                                <select name="purchase_unit_id" id="purchase_unit_id" class="form-control" required>
                                    <option selected disabled>-- Pilih Pembelian --</option>
                                    @foreach ($units as $item)
                                        <option value="{{ $item->id }}">{{ $item->short }} - {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Isi Per Dus / Karton <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" required name="content_per_unit">
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Harga Beli <span class="text-danger">*</span>
                                </label>
                                <input type="text" inputmode="numeric" required name="purchase_price" id="purchase_price"
                                    class="form-control rupiah">
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Harga Jual <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="selling_price" inputMode="numeric" id="selling_price" required
                                    class="form-control rupiah">
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Harga Jual Per Dus/Karton<span class="text-danger">*</span>
                                </label>
                                <input type="text" name="price_grosir" inputMode="numeric" id="price_grosir" required
                                    class="form-control rupiah">
                            </div>
                            <div class="mb-3">
                                <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" required name="stock" id="stock">
                            </div>
                            <div class="mb-3">
                                <label for="stock" class="form-label">Min Stok <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" required name="min_stock" id="stock">
                            </div>
                            <label for="" class="form-label">Masuk Pajak?</label>
                            <div class="mb-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="inputSelling" value="N" type="radio"
                                        name="is_pajak" id="radioDefault1" checked>
                                    <label class="form-check-label" for="radioDefault1">
                                        Tidak
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="inputBuying" type="radio" value="Y"
                                        name="is_pajak" id="radioDefault2">
                                    <label class="form-check-label" for="radioDefault2">
                                        Ya
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100" id="btn-save">Simpan</button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/jquery.number.min.js') }}"></script>

    <script>
        $('.rupiah').number(true, 0)
        $('#formProduct').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();

            $.ajax({
                url: "{{ route('product.store') }}",
                type: 'POST',
                data: formData,
                beforeSend: function() {
                    $('#btn-save').attr('disabled', true);
                    Swal.fire({
                        title: 'Mohon Tunggu...',
                        html: 'Sedang memproses data',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading(); // Ini yang memicu animasi loading
                        }
                    });
                },
                success: function(res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: res.desc,
                        timer: 2000 // Otomatis tutup dalam 2 detik
                    });
                    $('input.form-control').val('')
                    $('select.form-control').val('')
                    setTimeout(() => {

                        Swal.close();
                    }, 1800);
                },
                error: function(err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan: ' + err.responseJSON.message
                    });
                },
                complete: function() {

                    $('#btn-save').attr('disabled', false)
                }

            })
        })
    </script>
@endsection
