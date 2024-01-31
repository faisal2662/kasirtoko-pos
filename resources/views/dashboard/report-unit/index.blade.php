@extends('dashboard.layouts.main')

@section('container')
    @include('sweetalert::alert')

    <h3>Laporan Keuangan</h3>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item active">Laporan</li>
        </ol>
    </nav>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Keuangan</h5>
                        <button class="btn btn-primary" id="print" style="float:right; margin-right:25px;"><i
                                class="bi bi-printer"></i> Print</button>
                        <div class="row mb-3">
                            <div class="col-3">
                                <label for="awal" class="form-label">Awal</label>
                                <input type="date" name="awal" id="awal" class="form-control" required>

                            </div>
                            <div class="col-3">
                                <label for="akhir" class="form-label">Akhir</label>
                                <input type="date" name="akhir" id="akhir" class="form-control" required>
                            </div>
                            <div class="col-1 align-self-end">
                                <button class="btn btn-success" id="cek">Cek
                                </button>

                            </div>
                            <div class="form-text text-danger" style="display:none;" id="alert">* semua tanggal wajib
                                diisi
                            </div>
                        </div>
                        {{-- <a href="/dashboard/product-add" class="btn btn-primary mb-2">Tambah Data</a> --}}

                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Jumlah</th>
                                    {{-- <th scope="col">Harga Satuan</th>
                                    <th scope="col">Satuan</th>
                                    <th scope="col">Stok</th>
                                    <th scope="col">Aksi</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @foreach ($reports as $item)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $item->date }}</td>
                                        <td>@currency($item->total)</td>

                                    </tr>
                                @endforeach --}}

                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
