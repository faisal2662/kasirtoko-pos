@extends('dashboard.layouts.main')

@section('container')
    <h3>Riwayat Barang Masuk</h3>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item ">Barang</li>
            <li class="breadcrumb-item active">riwayat</li>
        </ol>
    </nav>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <a href="/dashboard/productIn" style="float:right;" class="btn btn-secondary mt-3">Kembali</a>
                        <a href="/dashboard/productIn-delete/{{ $id }}" style="float:right; margin-right:20px;"
                            class="btn btn-danger mt-3">Hapus Riwayat 1 Bulan</a>
                        <h5 class="card-title">Data Barang</h5>
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
                                    <th scope="col">Unit</th>
                                    {{-- <th scope="col">Satuan</th> --}}
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($histories->isEmpty())
                                    <tr>
                                        <td colspan="7">
                                            <p class="text-center fw-bold fw-light">Riwayat Belum Ada</p>
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($histories as $item)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->unit }}</td>
                                            <td>{{ $item->created_at->format('l, d-m-Y') }}</td>
                                            <td>{{ $item->created_at->format('H:i') }} Wib</td>
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- modal delete --}}
    @foreach ($histories as $item)
        <div class="modal" id="deleteRiwayat{{ $item->code_product_id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Anda yakin ingin menghapus {{ $item->name }} ?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        {{-- <a href="/dashboard/product-delete/{{ $item->slug }}" class="btn btn-danger">Hapus</a> --}}
                    </div>
                </div>
            </div>
        </div>
        <!-- End Vertically centered Modal-->
    @endforeach
@endsection
