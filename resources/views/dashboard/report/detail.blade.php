@extends('dashboard.layouts.main')


@section('title')
    Laporan Detail | Kasir
@endsection
@section('container')
    @include('sweetalert::alert')

    <h3>Laporan Keuangan Harian</h3>
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
                        <h5 class="card-title">Data Keuangan Detail </h5>
                        <button class="btn btn-primary" id="print" style="float:right; margin-right:25px;"><i
                                class="bi bi-printer"></i> Print</button>
                      <form action="" method="get">
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
                        {{-- <a href="/dashboard/product-add" class="btn btn-primary mb-2">Tambah Data</a> --}}

                        <!-- Table with stripped rows -->
                        <table class="table ">
                            <thead>
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">No. Invoice</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Kasir</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Bayar</th>
                                    <th scope="col">Kembali</th>
                                    <th scope="col">Metode Pembayaran</th>

                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $grandTotal = 0;
                                    $grandCashReceiv = 0;
                                    $grandCashChan = 0;

                                @endphp
                                @foreach ($sale as $item)
                                    @php
                                        $grandTotal += $item->total;
                                        $grandCashReceiv += $item->cash_received;
                                        $grandCashChan += $item->cash_change;

                                    @endphp
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $item->code_sale }}</td>
                                        <td>{{ $item->tanggal }}</td>
                                        <td>{{ $item->kasir_name }}</td>
                                        <td>@currency($item->total) </td>
                                        <td>@currency($item->cash_received) </td>
                                        <td>@currency($item->cash_change) </td>
                                        <td> {{ $item->payment->method }} </td>

                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="8"></td>
                                </tr>
                                <tr>
                                    <td colspan="4"><b>Grand Total</b></td>
                                    <td> @currency($grandTotal) </td>
                                    <td> @currency($grandCashReceiv) </td>
                                    <td colspan="2"> @currency($grandCashChan) </td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>
        </div>


    </section>

    <script></script>
@endsection
