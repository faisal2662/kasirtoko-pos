@extends('dashboard.layouts.main')


@section('title')
    Laporan Detail | Kasir
@endsection
@section('container')
    @include('sweetalert::alert')

    <h3>Laporan Produk Kena Pajak</h3>
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
                        <h5 class="card-title">Laporan Produk Kena Pajak </h5>
                        <form action="" method="get">
                            <div id="container-print" class="float-end" style="margin-right:20px;">
                                @if (isset(Request()->awal) && isset(Request()->akhir))
                                    <a class="btn btn-success" target="_blank"
                                        href="{{ Request()->getRequestUri() }}&?&export=export-excel"><i
                                            class="bi bi-file-spreadsheet"></i> Excel</a>
                                    <a class="btn btn-danger" target="_blank"
                                        href="{{ Request()->getRequestUri() }}&?&export=export-pdf"><i
                                            class="bi bi-filetype-pdf"></i> Pdf</a>
                                @else
                                    <a class="btn btn-success" target="_blank"
                                        href="{{ Request()->getRequestUri() }}?&export=export-excel"><i
                                            class="bi bi-file-spreadsheet"></i> Excel</a>
                                    <a class="btn btn-danger" target="_blank"
                                        href="{{ Request()->getRequestUri() }}?&export=export-pdf"><i
                                            class="bi bi-filetype-pdf"></i> Pdf</a>
                                @endif
                            </div>
                            <div class="row mb-3">

                                <div class="col-3">
                                    <label for="awal" class="form-label">Awal</label>
                                    <input type="date" value="{{ Request()->awal ?? '' }}" name="awal" id="awal"
                                        class="form-control" required>

                                </div>
                                <div class="col-3">
                                    <label for="akhir" class="form-label">Akhir</label>
                                    <input type="date" value="{{ Request()->akhir ?? '' }}" name="akhir" id="akhir"
                                        class="form-control" required>
                                </div>
                                <div class="col-1 align-self-end">
                                    <button class="btn btn-success" name="filter" value="true" id="cek">Cek
                                    </button>

                                </div>
                                <div class="form-text text-danger" style="display:none;" id="alert">* semua tanggal
                                    wajib
                                    diisi
                                </div>
                            </div>
                        </form>
                        {{-- href="/dashboard/product-add" class="btn btn-primary mb-2">Tambah Data</a> --}}

                        <!-- Table with stripped rows -->
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Produk</th>
                                    <th scope="col">Harga Jual</th>
                                    <th scope="col">Jumlah Terjual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $grandTotal = 0;
                                    $grandQty = 0;
                                    $no = 1;
                                @endphp

                                @foreach ($product as $tgl => $items)
                                    @foreach ($items as $index => $v)
                                        @php
                                            $grandTotal += $v->harga_jual;
                                            $grandQty += $v->qty;
                                        @endphp
                                        <tr>
                                            {{-- Hanya munculkan No dan Tanggal di baris pertama tiap grup tanggal --}}
                                            @if ($index === 0)
                                                <td rowspan="{{ $items->count() }}">{{ $no++ }}</td>
                                                <td style="text-align: center;vertical-align:middle;s"
                                                    rowspan="{{ $items->count() }}">
                                                    {{ \Carbon\Carbon::parse($tgl)->format('d/m/Y') }}</td>
                                            @endif

                                            <td>{{ $v->product->name ?? 'Produk Tidak Ditemukan' }}</td>
                                            <td>@currency($v->harga_jual)</td>
                                            <td>{{ $v->qty }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="font-weight: bold; background-color: #f8f9fa;">
                                    <td colspan="3" align="center">TOTAL KESELURUHAN</td>
                                    <td>@currency($grandTotal)</td>
                                    <td>{{ $grandQty }}</td>
                                </tr>
                            </tfoot>
                        </table>
                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>
        </div>


    </section>

    <script></script>
@endsection
