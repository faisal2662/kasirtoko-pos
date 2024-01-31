@extends('dashboard.layouts.main')

@section('container')
    <h3>Barang Masuk</h3>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item active">Barang</li>
        </ol>
    </nav>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Barang</h5>
                        {{-- <a href="/dashboard/product-add" class="btn btn-primary mb-2">Tambah Data</a> --}}
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Kategory</th>
                                    <th scope="col">Harga Satuan</th>
                                    <th scope="col">Satuan</th>
                                    <th scope="col">Stok</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $item)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $item->name_product }}</td>
                                        <td>{{ $item->category->name }}</td>
                                        <td>@currency($item->unit_price)</td>
                                        <td>{{ $item->short }}</td>
                                        <td>{{ $item->stock }}</td>
                                        <td><a href="" data-bs-toggle="modal"
                                                data-bs-target="#tambahStok{{ $item->slug }}" class="btn btn-primary"><i
                                                    class="bi bi-plus-circle"></i></a>
                                            || <a href="/dashboard/historyProduct/{{ $item->slug }}"
                                                class="btn btn-secondary"><i class="bi bi-clock-history"></i></a>
                                            {{-- || <a data-bs-toggle="modal" data-bs-target="#riwayatProduct{{ $item->slug }}"
                                                href="" class="btn btn-secondary"><i
                                                    class="bi bi-clock-history"></i></a> --}}

                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- modal tambah --}}
    @foreach ($products as $item)
        <div class="modal fade" id="tambahStok{{ $item->slug }}" tabindex="-1">

            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Stok : {{ $item->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="/dashboard/productIn-add" method="post">
                            @csrf
                            {{-- @foreach ($products as $item) --}}
                            <input type="text" value="{{ $item->slug }}" name="slug" hidden>
                            {{-- @endforeach --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">Stok</label>
                                <input type="text" name="unit" id="name" class="form-control" autofocus
                                    autocomplete="off">
                            </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    {{-- modal delete --}}
    @foreach ($products as $item)
        <div class="modal" id="deleteProduct{{ $item->slug }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Anda yakin ingin menghapus {{ $item->name }} ?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <a href="/dashboard/product-delete/{{ $item->slug }}" class="btn btn-danger">Hapus</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Vertically centered Modal-->
    @endforeach

    {{-- 
    @foreach ($products as $item)
        <!-- Modal Dialog Scrollable -->

        <div class="modal fade" id="riwayatProduct{{ $item->slug }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Riwayat Product Masuk : {{ $item->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Non omnis incidunt qui sed occaecati magni asperiores est mollitia. Soluta at et reprehenderit.

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div><!-- End Modal Dialog Scrollable-->
    @endforeach --}}
@endsection
