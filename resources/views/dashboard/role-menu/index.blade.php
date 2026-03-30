@extends('dashboard.layouts.main')
@section('container')
@section('title')
    Menu | kasir
@endsection
@include('sweetalert::alert')
<style>
    .text-stock {
        font-size: 13px;
        vertical-align: middle;
        color: #b8aeae;
    }

    .select2-container .select2-selection--single {
        padding: 5px !important;
        height: 40px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        top: 5px !important;
    }

    .select2-container--default .select2-selection--single {
        height: 35px;
    }
</style>

<link rel="stylesheet" href="{{ asset('select2/dist/css/select2.min.css') }}">

<h13>Penjualan</h3>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index">Home</a></li>
            <li class="breadcrumb-item active">Penjualan</li>
        </ol>
    </nav>

    <div class="row">

        <div class="col-12 col-md-4">
            <div class="card">
                <div class="card-body">
                    {{-- <div class="float-end mt-3"><i class="bx bx-refresh-cw-alt bx-spin"></i></div> --}}
                    <h5 class="card-title"> Penjualan </h5>
                    <form class="row g-3">
                        {{-- <div class="col-md-12">
                            <label for="name" class="form-label">Nama Pembeli</label>
                            <input type="text" name="name" class="form-control" id="name">
                        </div> --}}
                        <div class="col-md-12">
                            <label for="nambar" class="form-label">Cari Barang</label>
                            <li class="list-group-item">

                                <input type="text" class="form-control" autofocus id="nambar"
                                    onInput="fetchData()" name="nambar">
                            </li>
                            <ul class="list-group" id="tbodyfordata" style="width:100%;z-index:1; ">
                                {{-- <li class="list-group-item" onclick="tes()">lkfhjsd</li>
                                <li class="list-group-item">lkfhjsd</li> --}}
                            </ul>
                        </div>
                        <div style="display:none;">
                            <div class="col-md-12">
                                <label for="nambar" class="form-label">Kode Barang</label>
                                <input type="text" disabled name="name" class="form-control" id="kodbar">
                            </div>
                            <div class="col-6">
                                <label for="harsat" class="form-label">Harga Satuan</label>
                                <input type="text" class="form-control rupiah" id="harsat" disabled>
                            </div>
                            <div class="col-6">
                                <label for="jumbel" class="form-label">Jumlah Beli</label>
                                <input type="number" name="quantity" class="form-control" id="jumbel">
                            </div>
                            <div class="col-md-12">
                                <label for="jumlah" class="form-label">Jumlah</label>
                                <input type="text" class="form-control rupiah" name="jumlah" id="jumlah"
                                    disabled>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-primary" id="tambah"
                                    name="tambah">Tambah</button>
                                <button type="reset" class="btn btn-secondary" onclick="reset()">Reset</button>
                            </div>
                            {{-- <span class="jumlah_barang_${id_product} fw-bold" data-jumlah="1"
                            style="border-bottom: 1px solid #000;width:10px;">1</span> --}}
                        </div>
                    </form>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Barang populer</h5>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Product</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="container-populer-barang">

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-8 col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center fs-4"> {{ $setup->store_name }} </h5>
                    <p class="text-center" style="margin-top:-20px; font-size:13px;">
                        {{ $setup->address . ' - ' . $setup->city }}
                        <hr>
                    </p>
                    <form class="row g-3" action="/dashboard/sale" method="post" id="form-keranjang">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 col-12">
                                <div class="mb-3">
                                    <label class="form-label">Nama Pelanggan :</label>
                                    <select class="form-control customer-pelanggan" id="customer-pelanggan"
                                        name="customer_id" style="display: none;">
                                        <option value=""></option>
                                    </select>

                                    <input type="text" class="form-control customer-input" name="name">
                                </div>
                            </div>
                            <div class="col-md-5 col-10  mt-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                        id="pelanggan-checked">
                                    <label class="form-check-label" for="pelanggan-checked">Pelanggan Terdaftar</label>
                                </div>
                            </div>
                            <div class="col-2 col-md-3">
                                <div class="float-end mt-3"><a href="{{ route('invoice.index') }}" target="_blank"
                                        class="btn btn-warning"> <i class="bx bx-history"></i> Riwayat Transaksi </a>
                                </div>
                            </div>
                        </div>


                        <table class="table table-striped-columns" style="font-size:15px;">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th align="center">Satuan</th>
                                    <th align="center">Harga Satuan</th>
                                    <th align="center">Jumlah Barang</th>
                                    <th>Harga Total</th>
                                    <th>#</th>

                                </tr>
                            </thead>
                            <tbody id="keranjang">
                                {{-- <tr>

                                <td> <input type="hidden" name="product_id[]" class="input_product_id_1">
                                    Indomie</td>
                                <td class="text-center">
                                    <input type="hidden" name="harga_satuan[]" value="3000"
                                        class="input_harga_satuan_1">
                                    <span  class="harga_satuan_1 rupiah">3,000</span>
                                </td>
                                <td class="text-center">
                                    <span style="font-size:20px;cursor:pointer;margin-right:30px;"
                                    onclick="minusCurrent(this,1)"><i class="bx bx-minus-circle"></i></span>
                                    <input type="hidden" name="qty[]" class="input_jumlah_unit_1">
                                    <span class="jumlah_barang_1 fw-bold" data-jumlah="3"
                                        style="border-bottom: 1px solid #000;width:10px;">3</span>
                                    <span style="font-size:20px;cursor:pointer;margin-left:30px;"
                                        onclick="tambahCurrent(this,1)"><i class="bx bx-plus-circle"></i></span>
                                </td>
                                <td>

                                    <input type="hidden" name="harga_total[]" value="9000"
                                        class="input_harga_total_1">
                                    <span class="harga_total_1 rupiah"> 9,000 </span>
                                </td>
                            </tr> --}}
                            </tbody>
                            <hr>
                            <tr>
                                <td colspan="6" style="background-color:#bfbfbf;"></td>
                            </tr>
                            <tbody>
                                <tr>
                                    <td colspan="4">Sub Total:</td>
                                    <td class="text-center"> <input type="text" name="sub_total" id="subTotal"
                                            style="text-align: center;" class="form-control rupiah"
                                            readonly="readonly"> </td>
                                </tr>
                                <tr>
                                    <td colspan="4">Diskon % :</td>
                                    <td id="DiskonTotal"> <input type="text" oninput="inputDiskon(this)"
                                            class="form-control" name="diskon" id="input_diskon"> </td>

                                </tr>
                                <tr>
                                    <td colspan="4">Potongan Harga:</td>
                                    <td id="potonganHarga"> <input type="text" oninput="inputPothar(this)"
                                            class="form-control rupiah" name="potongan_harga" id="potongan_harga">
                                    </td>

                                </tr>
                                <tr>
                                    <td colspan="4">Jumlah Total:</td>
                                    <td id="jumlahTotal" class="text-center"></td>
                                    <input type="text" name="jumtot" hidden id="jumtotInput">
                                </tr>
                                <tr>
                                    <td colspan="4">Catatan Pembelian</td>
                                    <td>
                                        <textarea name="catatan" id="catatan" cols="30" rows="2" class="form-control"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">Metode Pembayaran</td>
                                    <td>
                                        <select name="metode_pembayaran" id="metode_pembayaran" class="form-control">
                                            <option value="cash">Cash</option>
                                            <option value="transfer">Transfer</option>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mt-1">
                            <div class="row">
                                <div class="col-5">
                                    <label for="bayar" class="form-label">Bayar</label>
                                    <input type="text" name="bayar_customer" class="form-control rupiah" required
                                        id="bayar">
                                </div>
                                <div class="col-5">
                                    <label for="kembalian" class="form-label">Kembalian</label>
                                    <input type="text" name="kembalian" readonly class="form-control"
                                        id="kembalian">
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" name="simpan"><i
                                    class="bi bi-floppy"></i> &nbsp; Simpan</button>
                            {{-- <input type="submit" value="Cetak" name="cetak" class="btn btn-secondary"> --}}
                            <button class="btn btn-secondary" onclick="savePrint(this)" type="button"
                                value="cetak" name="cetak"><i class="bi bi-printer"></i> &nbsp; Simpan &
                                Cetak</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <iframe id="print-frame" name="print-frame" style="display:none;"></iframe>
    @section('script')
        <script src="{{ asset('js/jquery.number.min.js') }}"></script>
        <script src="{{ asset('select2/dist/js/select2.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/qz-tray/qz-tray.js"></script>
        <script></script>
        <script>
            $(document).ready(function() {
                $('.rupiah').number(true, 0)
                $('body').addClass('toggle-sidebar')
                console.log(window.location.origin);
                // --- Konfigurasi ---
                const $se = $('#nambar');
                const $harsat = $('#harsat');
                const $kodbar = $('#kodbar');
                const $jumlah = $('#jumlah');
                const $jumbel = $('#jumbel');
                const $keranjang = $('#keranjang');
                const $tambah = $('#tambah');
                const $diskon = $('#DiskonTotal');
                const $diskonInput = $('#diskon');
                const $jumTotInput = $('#jumtotInput');
                const $subTot = $('#subTotal');
                const $jumlahTotal = $('#jumlahTotal');
                const $bayar = $('#bayar');
                const $kembalian = $('#kembalian');
                const MIN_BELANJA_DISKON = 100000; // Contoh: 100rb (sesuaikan jika 100 yang dimaksud adalah 100rb)
                const PERSEN_DISKON = 0.1; // 10%

                // configure print qz
                // qz.security.setCertificatePromise(function(resolve) {
                //     resolve(null);
                // });

                // qz.security.setSignaturePromise(function(toSign) {
                //     return function(resolve) {
                //         resolve();
                //     };
                // });

                // qz.security.setCertificatePromise(function(resolve, reject) {
                //     // fetch("/qz-cert")
                //     //     .then(res => res.text())
                //     //     .then(resolve)
                //     //     .catch(reject);
                //     fetch("{{ asset('qz/digital-certificate.txt') }}", {
                //             cache: 'no-store',
                //             headers: {
                //                 'Content-Type': 'text/plain'
                //             }
                //         })
                //         .then(function(data) {
                //             data.ok ? resolve(data.text()) : reject(data.text());
                //         });
                // });
                // qz.security.setSignatureAlgorithm("SHA512"); // Since 2.1

                // qz.security.setSignaturePromise(function(toSign) {
                //     return function(resolve, reject) {

                //         fetch("/qz-sign", {
                //                 method: "POST",
                //                 headers: {
                //                     "Content-Type": "application/json",
                //                     "X-CSRF-TOKEN": "{{ csrf_token() }}"
                //                 },
                //                 body: JSON.stringify({
                //                     data: toSign
                //                 })
                //             })
                //             .then(res => res.text())
                //             .then(resolve)
                //             .catch(reject);

                //     };
                // });

                // connectQZ().then(() => {
                //         console.log("QZ Connected");
                //     })
                //     .catch(err => console.error(err));


                // connectQZ()


                qz.security.setCertificatePromise(function(resolve, reject) {
                    //Preferred method - from server
                    fetch("{{ asset('qz/digital-certificate.txt') }}", {
                            cache: 'no-store',
                            headers: {
                                'Content-Type': 'text/plain'
                            }
                        })
                        .then(function(data) {
                            data.ok ? resolve(data.text()) : reject(data.text());
                        });

                    //Alternate method 1 - anonymous
                    //        resolve();  // remove this line in live environment

                    //Alternate method 2 - direct
                    // resolve("-----BEGIN CERTIFICATE-----\n" +
                    //             "MIIE9TCCAt2gAwIBAgIQNzkyMDI0MTIyMDE5MDI0NDANBgkqhkiG9w0BAQsFADCB\n" +
                    //             "mDELMAkGA1UEBhMCVVMxCzAJBgNVBAgMAk5ZMRswGQYDVQQKDBJRWiBJbmR1c3Ry\n" +
                    //             "aWVzLCBMTEMxGzAZBgNVBAsMElFaIEluZHVzdHJpZXMsIExMQzEZMBcGA1UEAwwQ\n" +
                    //             "cXppbmR1c3RyaWVzLmNvbTEnMCUGCSqGSIb3DQEJARYYc3VwcG9ydEBxemluZHVz\n" +
                    //             "dHJpZXMuY29tMB4XDTI0MTIyMDE5MDI0NFoXDTI5MTIyMDE4NTMxOVowga4xFjAU\n" +
                    //             "BgNVBAYMDVVuaXRlZCBTdGF0ZXMxCzAJBgNVBAgMAk5ZMRIwEAYDVQQHDAlDYW5h\n" +
                    //             "c3RvdGExGzAZBgNVBAoMElFaIEluZHVzdHJpZXMsIExMQzEbMBkGA1UECwwSUVog\n" +
                    //             "SW5kdXN0cmllcywgTExDMRswGQYDVQQDDBJRWiBJbmR1c3RyaWVzLCBMTEMxHDAa\n" +
                    //             "BgkqhkiG9w0BCQEMDXN1cHBvcnRAcXouaW8wggEiMA0GCSqGSIb3DQEBAQUAA4IB\n" +
                    //             "DwAwggEKAoIBAQC+j6ewVhtLHbY3uBNgqNB5DSz+QX9Pz5Dm46bI9vt/Q1Q6BL8I\n" +
                    //             "dhaxT2PA1AY0fqQgkzlSrwqNCjWZcrNZRw/e54FGM8zf3azbHrQif6d7Wo1JK5oN\n" +
                    //             "kI3jdB54YVwHIAt6i3BcLIvyOHsPnrKjlpROz72Kx1kK5g0gLDuH5RYVM9KFK+HR\n" +
                    //             "fBc3JSfeg8nUkTqYJVzlT5AGRWPXeDWloqQqSyuB1t8DihNBReWyJHQ7a4yerLOI\n" +
                    //             "J6N0jAlLDx9yt9UznAxnoO+7tKBfxCbNJerGfePMOwRKq0gx+r8M/FTrAoj+yc+T\n" +
                    //             "SOYtuY/VZ79HCTP/vLgm1pGyrta1we24fVezAgMBAAGjIzAhMB8GA1UdIwQYMBaA\n" +
                    //             "FJCmULeE1LnqX/IFhBN4ReipdVRcMA0GCSqGSIb3DQEBCwUAA4ICAQAMvfp931Zt\n" +
                    //             "PgfqGXSrsM+GAVBxcRVm14MyldWfRr+MVaFZ6cH7c+fSs8hUt2qNPwHrnpK9eev5\n" +
                    //             "MPUL27hjfiTPwv1ojLJ180aMO0ZAfPfnKeLO8uTzY7GiPQeGK7Qh39kX9XxEOidG\n" +
                    //             "rMwfllZ6jJReS0ZGaX8LUXhh9RHGSYJhxgyUV7clB/dJch8Bbcd+DOxwc1POUHx1\n" +
                    //             "wWExKkoWzHCCYNvqxLC9p1eO2Elz9J9ynDjXtCBl7lssnoSUKtahBCKgN5tYmZZK\n" +
                    //             "NErKPQpbYk5yTEK1gybxhup8i2sGEJXZ9HRJLAl0UxB+eCu1ExWv7eGbcbIZJbeh\n" +
                    //             "bwRf03fatsqzCQbGboLWtMQfcxHrEu+5MdZwOFx8i+c0c2WYad2MkkzGYHBVHPtY\n" +
                    //             "o+PR61uIwJC2mNkPpX94CIFxSHyZumttyVKF4AhIPm9IMGTHaIr5M39zesQpVc7N\n" +
                    //             "VIgxmMuePBrLyh6vKvuqD7W3S2HWA/8IUX703tdhoXhv5lNo1j0oywSrrUkCvUvJ\n" +
                    //             "FjPS8+VUtVZNl7SVetQTexdcUwoADj6c1UwL9QWItskJ5Myesco3ZY0O+3QbgCuQ\n" +
                    //             "SRqN5D0qdaLNMdEwh1YekUp4i1jm0jzPzia+WvJrW1k1ZafV6ep+YkMBkC1SFYFw\n" +
                    //             "1Mdy+fYGyXlSn/Mvou//SSb0fUMIpXE9NA==\n" +
                    //             "-----END CERTIFICATE-----\n" +
                    //             "--START INTERMEDIATE CERT--\n" +
                    //             "-----BEGIN CERTIFICATE-----\n" +
                    //             "MIIFEjCCA/qgAwIBAgICEAAwDQYJKoZIhvcNAQELBQAwgawxCzAJBgNVBAYTAlVT\n" +
                    //             "MQswCQYDVQQIDAJOWTESMBAGA1UEBwwJQ2FuYXN0b3RhMRswGQYDVQQKDBJRWiBJ\n" +
                    //             "bmR1c3RyaWVzLCBMTEMxGzAZBgNVBAsMElFaIEluZHVzdHJpZXMsIExMQzEZMBcG\n" +
                    //             "A1UEAwwQcXppbmR1c3RyaWVzLmNvbTEnMCUGCSqGSIb3DQEJARYYc3VwcG9ydEBx\n" +
                    //             "emluZHVzdHJpZXMuY29tMB4XDTE1MDMwMjAwNTAxOFoXDTM1MDMwMjAwNTAxOFow\n" +
                    //             "gZgxCzAJBgNVBAYTAlVTMQswCQYDVQQIDAJOWTEbMBkGA1UECgwSUVogSW5kdXN0\n" +
                    //             "cmllcywgTExDMRswGQYDVQQLDBJRWiBJbmR1c3RyaWVzLCBMTEMxGTAXBgNVBAMM\n" +
                    //             "EHF6aW5kdXN0cmllcy5jb20xJzAlBgkqhkiG9w0BCQEWGHN1cHBvcnRAcXppbmR1\n" +
                    //             "c3RyaWVzLmNvbTCCAiIwDQYJKoZIhvcNAQEBBQADggIPADCCAgoCggIBANTDgNLU\n" +
                    //             "iohl/rQoZ2bTMHVEk1mA020LYhgfWjO0+GsLlbg5SvWVFWkv4ZgffuVRXLHrwz1H\n" +
                    //             "YpMyo+Zh8ksJF9ssJWCwQGO5ciM6dmoryyB0VZHGY1blewdMuxieXP7Kr6XD3GRM\n" +
                    //             "GAhEwTxjUzI3ksuRunX4IcnRXKYkg5pjs4nLEhXtIZWDLiXPUsyUAEq1U1qdL1AH\n" +
                    //             "EtdK/L3zLATnhPB6ZiM+HzNG4aAPynSA38fpeeZ4R0tINMpFThwNgGUsxYKsP9kh\n" +
                    //             "0gxGl8YHL6ZzC7BC8FXIB/0Wteng0+XLAVto56Pyxt7BdxtNVuVNNXgkCi9tMqVX\n" +
                    //             "xOk3oIvODDt0UoQUZ/umUuoMuOLekYUpZVk4utCqXXlB4mVfS5/zWB6nVxFX8Io1\n" +
                    //             "9FOiDLTwZVtBmzmeikzb6o1QLp9F2TAvlf8+DIGDOo0DpPQUtOUyLPCh5hBaDGFE\n" +
                    //             "ZhE56qPCBiQIc4T2klWX/80C5NZnd/tJNxjyUyk7bjdDzhzT10CGRAsqxAnsjvMD\n" +
                    //             "2KcMf3oXN4PNgyfpbfq2ipxJ1u777Gpbzyf0xoKwH9FYigmqfRH2N2pEdiYawKrX\n" +
                    //             "6pyXzGM4cvQ5X1Yxf2x/+xdTLdVaLnZgwrdqwFYmDejGAldXlYDl3jbBHVM1v+uY\n" +
                    //             "5ItGTjk+3vLrxmvGy5XFVG+8fF/xaVfo5TW5AgMBAAGjUDBOMB0GA1UdDgQWBBSQ\n" +
                    //             "plC3hNS56l/yBYQTeEXoqXVUXDAfBgNVHSMEGDAWgBQDRcZNwPqOqQvagw9BpW0S\n" +
                    //             "BkOpXjAMBgNVHRMEBTADAQH/MA0GCSqGSIb3DQEBCwUAA4IBAQAJIO8SiNr9jpLQ\n" +
                    //             "eUsFUmbueoxyI5L+P5eV92ceVOJ2tAlBA13vzF1NWlpSlrMmQcVUE/K4D01qtr0k\n" +
                    //             "gDs6LUHvj2XXLpyEogitbBgipkQpwCTJVfC9bWYBwEotC7Y8mVjjEV7uXAT71GKT\n" +
                    //             "x8XlB9maf+BTZGgyoulA5pTYJ++7s/xX9gzSWCa+eXGcjguBtYYXaAjjAqFGRAvu\n" +
                    //             "pz1yrDWcA6H94HeErJKUXBakS0Jm/V33JDuVXY+aZ8EQi2kV82aZbNdXll/R6iGw\n" +
                    //             "2ur4rDErnHsiphBgZB71C5FD4cdfSONTsYxmPmyUb5T+KLUouxZ9B0Wh28ucc1Lp\n" +
                    //             "rbO7BnjW\n" +
                    //             "-----END CERTIFICATE-----\n");
                });

                qz.security.setSignatureAlgorithm("SHA512"); // Since 2.1
                qz.security.setSignaturePromise(function(toSign) {
                    return function(resolve, reject) {
                        //Preferred method - from server
                        fetch("/qz-sign?data=" + toSign, {
                                cache: 'no-store',
                                headers: {
                                    'Content-Type': 'text/plain'
                                }
                            })
                            .then(function(data) {
                                data.ok ? resolve(data.text()) : reject(data.text());
                            });

                        //Alternate method - unsigned
                        // resolve(); // remove this line in live environment
                    };
                });

                // launchQZ()
                /// Connection ///
                // function launchQZ() {
                //     if (!qz.websocket.isActive()) {
                //         window.location.assign("qz:launch");
                //         //Retry 5 times, pausing 1 second between each attempt
                //         startConnection({
                //             retries: 5,
                //             delay: 1
                //         });
                //     }
                // }

                startConnection({
                    retries: 5,
                    delay: 1
                });

                function startConnection(config) {
                    // var host = $('#connectionHost').val().trim();
                    var host = 'localhost';

                    var usingSecure = null;
                    // var usingSecure = $("#connectionUsingSecure").prop('checked');

                    // Connect to a print-server instance, if specified
                    if (host != "" && host != 'localhost') {
                        if (config) {
                            config.host = host;
                            config.usingSecure = usingSecure;
                        } else {
                            config = {
                                host: host,
                                usingSecure: usingSecure
                            };
                        }
                    }

                    if (!qz.websocket.isActive()) {
                        // updateState('Waiting', 'default');

                        qz.websocket.connect(config).then(function() {
                            // updateState('Active', 'success');
                            // findVersion();
                        }).catch(handleConnectionError);
                    } else {
                        displayMessage('An active connection with QZ already exists.', 'alert-warning');
                    }
                }




                // --- Fungsi Helper (Utility) ---
                let i = 0;


                // Mengubah angka murni ke format IDR (Contoh: 150000 -> Rp 150.000,00)
                const formatKeRupiah = (angka) => {
                    return new Intl.NumberFormat("id-ID", {
                        style: "currency",
                        currency: "IDR",
                        minimumFractionDigits: 0
                    }).format(angka);
                };

                // Mengambil angka saja dari string Rp (Contoh: "Rp 150.000" -> 150000)
                const parseAngka = (stringRupiah) => {
                    return Number(stringRupiah.replace(/[^0-9]/g, "")) || 0;
                };

                // --- Fungsi Utama ---

                function hitungUlangTotal() {
                    let subtotal = 0;

                    // Ambil setiap harga total di baris keranjang
                    $('.hartotal').each(function() {
                        subtotal += parseAngka($(this).text());
                    });

                    let nilaiDiskon = 0;
                    let totalAkhir = subtotal;

                    // Logika Diskon: Jika belanja >= 100.000 (sesuaikan angka 100 ini)
                    if (subtotal >= MIN_BELANJA_DISKON) {
                        nilaiDiskon = subtotal * PERSEN_DISKON;
                        totalAkhir = subtotal - nilaiDiskon;

                        $('#DiskonTotal').text('10%');
                        $('#diskon').val(PERSEN_DISKON);
                    } else {
                        $('#DiskonTotal').text('0%');
                        $('#diskon').val(0);
                    }

                    // Update Tampilan
                    $('#subTotal').val(formatKeRupiah(subtotal));
                    $('#jumlahTotal').text(formatKeRupiah(totalAkhir));
                    $('#jumtotInput').val(totalAkhir); // Simpan angka murni untuk database

                    // Update kembalian jika field bayar sudah terisi
                    hitungKembalian();
                }

                function hitungKembalian() {
                    const bayar = parseAngka($('#bayar').val());
                    const totalTagihan = parseAngka($('#jumlahTotal').text());
                    const sisa = bayar - totalTagihan;

                    if (bayar > 0) {
                        $('#kembalian').val($.number(sisa, 0, ','));
                    } else {
                        $('#kembalian').val('');
                    }
                }

                // --- Event Handlers ---
                // Fungsi Fetch Data (Pencarian Produk)
                // Dipanggil melalui atribut 'onkeyup' atau listener di HTML
                window.fetchData = function() {

                    let nambar = $se.val();
                    if (nambar == '' || nambar == null) return false;
                    const url = "{{ route('search.product') }}?search=" + nambar;

                    $.getJSON(url, function(res) {

                        const $tbodyref = $('#tbodyfordata');
                        $tbodyref.empty().show();

                        $.each(res, function(index, data) {

                            let harga_satuan = Number(data.selling_price) / Number(data
                                .content_per_unit);

                            let $li = $('<li></li>')
                                .text(data.name)
                                .css('cursor', 'pointer')
                                .data('info', {
                                    name: data.name,
                                    id: data.id,
                                    harga: data.selling_price,
                                    harga_beli: data.purchase_price,
                                    satuanKecil: data.unit_id,
                                    satuanBesar: data.purchase_unit_id,
                                    satuanKecilName: data.short,
                                    satuanBesarName: data.purchase_name
                                })
                                .addClass('list-group-item hasil');

                            let span = $('<span></span>')

                                .addClass('float-end')
                                .addClass('text-stock')
                                .text('stock: ' + data.stock);

                            $li.append(span).appendTo($tbodyref);
                            // $li.appendTo($tbodyref);

                            $li.on('click', function() {
                                const d = $(this).data('info');
                                pilihData(d.name, d.id, d.harga, d.harga_beli, d
                                    .satuanKecil, d.satuanBesar, d.satuanKecilName, d
                                    .satuanBesarName);

                                $se.val('').focus();
                                $tbodyref.hide();
                            });

                        });

                    });

                };

                getPopuler()


                $tambah.on('click', function() {

                })

                // Saat mengetik jumlah bayar
                $('#bayar').on('keyup', function() {
                    let nominal = parseAngka($(this).val());
                    // $(this).val(formatKeRupiah(nominal).replace(",00", "")); // Format input saat diketik
                    hitungKembalian();
                });

                // Delegasi event untuk tombol hapus (agar baris baru bisa dihapus)
                $('#keranjang').on('click', '.remove-btn', function() {
                    $(this).closest('tr').fadeOut(300, function() {
                        $(this).remove();
                        hitungUlangTotal();
                    });
                });

                // Tambahkan fungsi ini ke dalam event klik 'tambah' Anda
                // hitungUlangTotal();
                getCustomer();
                setInterval(() => {
                    getPopuler();

                }, 60000);
            });

            function savePrint(obj) {
                // obj.preventDefault();
                let bayar = parseInt($('#bayar').val().replace(',', ''));

                let jumtot = $('#jumtotInput').val();

                if (bayar < jumtot) {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian !',
                        text: 'Bayar kurang dari jumlah total bayar'
                    })
                    return false;
                } else if (jumtot == 0 || jumtot == '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian !',
                        text: 'Jumlah total kosong'
                    })
                    return false;
                }


                Swal.fire({
                    title: "<h5>Kamu yakin ingin melanjutkan proses ini ?</h5>",
                    showCancelButton: true,
                    confirmButtonText: "Ya",
                }).then((result) => {
                    if (result.isConfirmed) {
                        let formData = $('#form-keranjang').serialize();
                        $.ajax({
                            url: "{{ route('sale.store') }}",
                            type: 'POST',
                            data: formData,
                            beforeSend: function() {
                                $('#btn-save').attr('disabled', true);
                                Swal.fire({
                                    title: 'Mohon Tunggu...',
                                    html: 'Sedang memproses data',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal
                                            .showLoading(); // Ini yang memicu animasi loading
                                    }
                                });
                            },
                            success: function(res) {
                                printStruk(res.setup, res.sale);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: res.desc,
                                    time: 2000
                                });
                                // Ambil template URL dari Laravel
                                // let url = "{{ route('print.struk', ':id') }}";

                                // // Ganti :id dengan ID yang didapat dari response simpan kasir
                                // url = url.replace(':id', res.sale.id);

                                // // Buka di tab baru
                                // // window.open(url, '_blank');
                                // $('#print-frame').attr('src', url);


                                $('#keranjang').html('')
                                $('#bayar').val('')
                                $('#kembalian').val('')
                                $('#catatan').val('')
                                $('#input_diskon').val('')
                                $('#potongan_harga').val('')
                                jumlahSubTotal();
                                $('#nambar').focus();
                                $('.customer-input').val('');
                                $('#customer-pelanggan').val(null).trigger('change');

                                setTimeout(() => {
                                    Swal.close();
                                }, 1500);
                            },
                            error: function(err) {
                                Swal.close();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal !',
                                    text: err.responseJSON.message
                                })
                            },
                            complete: function() {

                            }
                        })
                    }
                    return false;
                });
            }

            $('#form-keranjang').on('submit', function(e) {
                e.preventDefault();
                let bayar = parseInt($('#bayar').val().replace(',', ''));

                let jumtot = $('#jumtotInput').val();

                if (bayar < jumtot) {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian !',
                        text: 'Bayar kurang dari jumlah total bayar'
                    })
                    return false;
                } else if (jumtot == 0 || jumtot == '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian !',
                        text: 'Jumlah total kosong'
                    })
                    return false;
                }

                Swal.fire({
                    title: "<h5>Kamu yakin ingin melanjutkan proses ini ?</h5>",
                    showCancelButton: true,
                    confirmButtonText: "Ya",
                }).then((result) => {
                    if (result.isConfirmed) {
                        let formData = $(this).serialize();
                        $.ajax({
                            url: "{{ route('sale.store') }}",
                            type: 'POST',
                            data: formData,
                            beforeSend: function() {
                                $('#btn-save').attr('disabled', true);
                                Swal.fire({
                                    title: 'Mohon Tunggu...',
                                    html: 'Sedang memproses data',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal
                                            .showLoading(); // Ini yang memicu animasi loading
                                    }
                                });
                            },
                            success: function(res) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil !',
                                    text: res.desc,
                                    time: 2000
                                });

                                $('#keranjang').html('')
                                $('#bayar').val('')
                                $('#kembalian').val('')
                                $('#catatan').val('')
                                $('#input_diskon').val('')
                                $('#potongan_harga').val('')
                                jumlahSubTotal();
                                $('#nambar').focus();
                                $('.customer-input').val('');
                                $('#customer-pelanggan').val(null).trigger('change');

                                setTimeout(() => {
                                    Swal.close();
                                }, 1500);
                            },
                            error: function(err) {
                                Swal.close();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal !',
                                    text: err.responseJSON.message
                                })
                            },
                            complete: function() {

                            }
                        })
                    }
                    return false;
                });


            })


            function getCustomer() {
                $.ajax({
                    url: "{{ route('customer.getData') }}",
                    type: 'get',
                    success: function(res) {
                        let data = res.data;
                        let $el = $('.customer-pelanggan');

                        // 1. Kosongkan dropdown dan tambahkan placeholder default
                        $el.empty().append('<option value=""></option>');

                        // 2. Looping data
                        $.each(data, function(i, v) {
                            $el.append(`<option value="${v.id}">${v.name}</option>`);
                        });

                        // 3. Inisialisasi Select2 (Perhatikan koma setelah placeholder)
                        $el.select2({
                            placeholder: 'Pilih Pelanggan',
                            allowClear: true,
                            width: '100%' // Agar responsif
                        });
                        $('#customer-pelanggan').next('.select2-container').hide();
                    },
                    error: function(err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan: ' + (err.responseJSON?.message ||
                                'Gagal mengambil data')
                        });
                    }
                });
            }


            $('#pelanggan-checked').on('change', function() {
                let status = $(this).prop('checked');

                if (status) {
                    // Mode: Pilih dari Database
                    $('.customer-input').hide().prop('required', false).val('');
                    $('.customer-pelanggan').show();

                    // Jika menggunakan Select2, kita harus menampilkan kontainernya
                    if ($('#customer-pelanggan').data('select2')) {
                        $('#customer-pelanggan').next('.select2-container').show();
                    }
                } else {
                    // Mode: Input Manual
                    $('.customer-input').show().prop('required', true);
                    $('.customer-pelanggan').hide();

                    // Sembunyikan container Select2 jika ada
                    if ($('#customer-pelanggan').data('select2')) {
                        $('#customer-pelanggan').next('.select2-container').hide();
                    }

                    // Reset pilihan dropdown
                    $('#customer-pelanggan').val(null).trigger('change');
                }
            });

            function pilihData(name_product, id_product, harga_satuan, harga_beli, satuanKecil, satuanBesar, satuanKecilName,
                satuanBesarName) {
                let checkProduct = $('#keranjang').find('.input_product_id_' + id_product).length;
                let getBaris = $('#keranjang').find('.baris_keranjang:last');
                let baris = 1;
                if (getBaris.length > 0) {
                    baris = parseInt(getBaris.val()) + 1;
                }

                let checkSatuan = $('#keranjang').find('#satuanKecil_' + id_product + ':checked');
                if (checkProduct > 0 && checkSatuan.length > 0) {
                    let ambilQty = $('#keranjang').find('.input_jumlah_unit_' + id_product).val();
                    let ambilHartot = $('#keranjang').find('.input_harga_total_' + id_product).val();
                    // menaruh data baru
                    let totalQty = parseInt(ambilQty) + 1;
                    let totalHartot = parseInt(ambilHartot) + harga_satuan;
                    // span quantity
                    // $('#keranjang').find('.jumlah_barang_' + id_product).data('jumlah', totalQty).text(totalQty)
                    // input quantity
                    $('#keranjang').find('.input_jumlah_unit_' + id_product).val(totalQty);
                    // span harga total
                    $('#keranjang').find('.harga_total_' + id_product).text($.number(totalHartot, 0));
                    // input harga total
                    $('#keranjang').find('.input_harga_total_' + id_product).val(totalHartot)

                } else {

                    $('#keranjang').append(
                        `
                 <tr>
                    <input type="hidden" name="baris[]" class="baris_keranjang" value="${baris}">
                    <td> <input type="hidden" value="${id_product}" name="product_id[]" class="input_product_id_${id_product}">
                        ${name_product}</td>
                    <td class="text-center">

                         <div class="form-check form-check-inline">
                            <input class="form-check-input satuan_has_used_${id_product} unit_check_kecil_${baris}" data-harga="${harga_satuan}" id="satuanKecil_${id_product}" onclick="satuanKecil(this,${id_product})" value="${satuanKecil}" type="radio"
                                name="unit_used_${baris}"  checked>
                            <label class="form-check-label" for="satuanKecil">
                               ${satuanKecilName}
                            </label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input satuan_has_used_${id_product} unit_check_besar_${baris}" data-harga="${harga_beli}" id="satuanBesar_${id_product}" onclick="satuanBesar(this,${id_product})" type="radio" value="${satuanBesar}"
                                name="unit_used_${baris}" >
                            <label class="form-check-label" for="satuanBesar">
                                ${satuanBesarName}
                            </label>
                        </div>
                    </td>
                    <td class="text-center">
                        <input type="hidden" name="harga_satuan[]" value="${harga_satuan}"
                            class="input_harga_satuan_${id_product}">
                        <span  class="harga_satuan_${id_product} rupiah">${$.number(harga_satuan,0)}</span>
                    </td>
                    <td class="text-center">
                        <span style="font-size:20px;cursor:pointer;margin-right:10px;"
                        onclick="minusCurrent(this,${id_product})"><i class="bx bx-minus-circle"></i></span>
                        <input type="text" oninput="inputJumbar(this, ${id_product})" inputMode="numeric" style="text-align:center;width:30px;border-top:none;border-left:none;border-right:none;" name="qty[]" class="input_jumlah_unit_${id_product}" value="1">

                        <span style="font-size:20px;cursor:pointer;margin-left:10px;"
                            onclick="tambahCurrent(this,${id_product})"><i class="bx bx-plus-circle"></i></span>
                    </td>
                    <td class="text-center" width="250">

                        <input type="hidden" name="harga_total[]" value="${harga_satuan}"
                            class="input_harga_total_${id_product} harga_total_multi">
                        <span class="harga_total_${id_product} rupiah text-center">${$.number(harga_satuan,0)} </span>
                    </td>
                    <td class="text-center" width="30">

                     <span style="cursor:pointer" class="text-danger" onclick="hapusBaris(this)"> <i class="bx bx-trash"></i></span>
                    </td>
                </tr>
                `
                    )

                }

                jumlahSubTotal();
            }

            function jumlahSubTotal() {
                let total = 0;

                $('.harga_total_multi').each(function() {
                    let val = parseInt($(this).val()) || 0;
                    total += val;
                })
                $('#subTotal').val($.number(total, 0))
                let valDiskon = $('#input_diskon').val();
                let valPothar = $('#potongan_harga').val();

                let calDisc = total * valDiskon / 100;
                let resDisc = total - calDisc;
                let resPothar = resDisc - valPothar;

                $('#jumlahTotal').text($.number(resPothar, 0))
                $('#jumtotInput').val(resPothar)
            }

            function inputDiskon(obj) {
                obj.value = obj.value.replace(/[^0-9|,|.]/g, '');

                let valSubs = parseInt($('#subTotal').val()) || 0;
                let valPothar = $('#potongan_harga').val();
                let calDisc = valSubs * obj.value / 100;
                let resDisc = valSubs - calDisc;
                let resPothar = resDisc - valPothar;

                $('#jumlahTotal').text($.number(resPothar, 0))
                $('#jumtotInput').val(resPothar)
            }

            function inputPothar(obj) {
                obj.value = obj.value.replace(/[^0-9]/g, '');

                let valDisc = $('#input_diskon').val()
                let valSubs = parseInt($('#subTotal').val()) || 0;
                let calDisc = valSubs * valDisc / 100;
                let resDisc = valSubs - calDisc;
                let resPothar = resDisc - obj.value;

                $('#jumlahTotal').text($.number(resPothar, 0))
                $('#jumtotInput').val(resPothar)

            }

            function inputJumbar(obj, id_product) {
                obj.value = obj.value.replace(/[^0-9]/g, '');

                let total = parseInt($(obj).closest('tr').find('.input_harga_satuan_' + id_product).val()) * obj.value;
                $(obj).closest('tr').find('.input_harga_total_' + id_product).val(total)
                $(obj).closest('tr').find('.harga_total_' + id_product).text($.number(total, 0))
                jumlahSubTotal()
            }

            function minusCurrent(obj, baris) {
                // 1. Cari elemen span dengan class .jumlah_barang di kolom yang sama
                let labelJumlah = $(obj).closest('tr').find('.input_jumlah_unit_' + baris);
                // let labelJumlah = $(obj).closest('tr').find('.jumlah_barang_' + baris );

                // 2. Ambil nilai angka saat ini dari teks span
                let currentCount = parseInt(labelJumlah.val()) || 0;

                // 3. Validasi: Jangan biarkan kurang dari 1 (atau 0, tergantung kebutuhan)
                if (currentCount > 1) {
                    let minCurrent = currentCount - 1;

                    // 4. Update tampilan angka di layar
                    labelJumlah.text(minCurrent);

                    // 5. Update atribut data-jumlah (untuk keperluan backend/logic lainnya)
                    labelJumlah.data('jumlah', minCurrent);
                    $(obj).closest('tr').find('.input_jumlah_unit_' + baris).val(minCurrent);

                    // 6. ambil harga satuan barang
                    let harga_satuan = parseInt($(obj).closest('tr').find('.satuan_has_used_' + baris + ':checked').data(
                        'harga')) || 0;

                    // kalkulasi jumlah total
                    let total_harga = harga_satuan * minCurrent;

                    // set to baris
                    $(obj).closest('tr').find('.input_harga_total_' + baris).val(total_harga)
                    total_harga = $.number(total_harga, 0, ',')

                    $(obj).closest('tr').find('.harga_total_' + baris).text(total_harga)
                    jumlahSubTotal()
                }
            }

            function tambahCurrent(obj, baris) {
                // 1. Cari elemen span dengan class .jumlah_barang di kolom yang sama
                let labelJumlah = $(obj).closest('tr').find('.input_jumlah_unit_' + baris);
                // let labelJumlah = $(obj).closest('tr').find('.jumlah_barang_' + baris);

                // 2. Ambil nilai angka saat ini
                let currentCount = parseInt(labelJumlah.val()) || 0;

                // 3. Tambahkan satu
                let plusCurrent = currentCount + 1;

                // 4. Update tampilan angka di layar
                labelJumlah.text(plusCurrent);

                // 5. Update atribut data-jumlah
                labelJumlah.data('jumlah', plusCurrent);
                labelJumlah.val(plusCurrent)
                $(obj).closest('tr').find('.input_jumlah_unit_' + baris).val(plusCurrent)

                // 6. ambil harga satuan barang
                let harga_satuan = parseInt($(obj).closest('tr').find('.satuan_has_used_' + baris + ':checked').data(
                    'harga')) || 0;

                // kalkulasi jumlah total
                let total_harga = harga_satuan * plusCurrent;

                // set to baris
                $(obj).closest('tr').find('.input_harga_total_' + baris).val(total_harga)
                $(obj).closest('tr').find('.harga_total_' + baris).text($.number(total_harga, 0, ','))
                jumlahSubTotal()

            }

            function satuanKecil(obj, id_product) {
                let harga_satuan = parseInt($(obj).data('harga'));
                let baris = $(obj).closest('tr');
                let harga_total = baris.find('.input_harga_total_' + id_product);
                let label_hartot = baris.find('.harga_total_' + id_product);
                let qty = baris.find('.input_jumlah_unit_' + id_product).val();

                // update data
                let new_harga_total = harga_satuan * qty;
                harga_total.val(new_harga_total);
                label_hartot.text($.number(new_harga_total, 0, ','))
                $(obj).closest('tr').find('.input_harga_satuan_' + id_product).val(harga_satuan)
                $(obj).closest('tr').find('.harga_satuan_' + id_product).text('')
                $(obj).closest('tr').find('.harga_satuan_' + id_product).text($.number(harga_satuan, 0, ','))
                jumlahSubTotal();
            }


            function satuanBesar(obj, id_product) {
                let harga_satuan = parseInt($(obj).data('harga'));
                let baris = $(obj).closest('tr');
                let harga_total = baris.find('.input_harga_total_' + id_product);
                let label_hartot = baris.find('.harga_total_' + id_product);
                let qty = baris.find('.input_jumlah_unit_' + id_product).val();

                // update data
                let new_harga_total = harga_satuan * qty;

                harga_total.val(new_harga_total);
                label_hartot.text($.number(new_harga_total, 0, ','))
                $(obj).closest('tr').find('.input_harga_satuan_' + id_product).val(harga_satuan)
                $(obj).closest('tr').find('.harga_satuan_' + id_product).text('')
                $(obj).closest('tr').find('.harga_satuan_' + id_product).text($.number(harga_satuan, 0, ','))
                jumlahSubTotal();
            }

            function getPopuler() {
                $.ajax({
                    url: "{{ route('sale.getPopuler') }}",
                    type: 'get',
                    success: function(res) {
                        let data = res.data;
                        $('#container-populer-barang').html('');
                        let baris = '';
                        let no = 1;
                        if (data.length > 0) {
                            $.map(data, function(v, i) {

                                baris += `
                            <tr>
                                    <td>${no++}</td>
                                    <td>${v.product.name} <span class="float-end text-stock">stock:${v.product.stock} </span> </td>
                                    <td><button class="btn btn-primary" onclick="pilihData('${v.product.name}',${v.product.id},${v.product.selling_price}, ${v.product.purchase_price} , ${v.product.unit_id}, ${v.product.purchase_unit_id}, '${v.product.short}', '${v.product.purchase_name}')">Pilih</button></td>
                                </tr>
                            `;
                            })
                            $('#container-populer-barang').html(baris)
                        } else {
                            baris = `
                          <tr>
                                    <td colspan="3" class="text-center"> data Belum Tersedia </td>

                                </tr>
                        `;
                            $('#container-populer-barang').html(baris)
                        }
                    },
                    error: function(err) {
                        console.log(err);
                        if (err.status == 401) {

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan: Silahkan Login Kembali'
                            });
                        } else {

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan: ' + err.responseJSON.message
                            });
                        }
                    }
                })
            }

            function hapusBaris(obj) {
                $(obj).closest('tr').remove()
            }


            // function savePrint(obj) {
            //     // obj.preventDefault();
            //     let bayar = parseInt($('#bayar').val().replace(',', ''));

            //     let jumtot = $('#jumtotInput').val();

            //     if (bayar < jumtot) {

            //         Swal.fire({
            //             icon: 'warning',
            //             title: 'Perhatian !',
            //             text: 'Bayar kurang dari jumlah total bayar'
            //         })
            //         return false;
            //     } else if (jumtot == 0 || jumtot == '') {
            //         Swal.fire({
            //             icon: 'warning',
            //             title: 'Perhatian !',
            //             text: 'Jumlah total kosong'
            //         })
            //         return false;
            //     }

            //     Swal.fire({
            //         title: "<h5>Kamu yakin ingin melanjutkan proses ini ?</h5>",
            //         showCancelButton: true,
            //         confirmButtonText: "Ya",
            //     }).then((result) => {
            //         if (result.isConfirmed) {
            //             let formData = $('#form-keranjang').serialize();
            //             $.ajax({
            //                 url: "{{ route('sale.store') }}",
            //                 type: 'POST',
            //                 data: formData,
            //                 beforeSend: function() {
            //                     $('#btn-save').attr('disabled', true);
            //                     Swal.fire({
            //                         title: 'Mohon Tunggu...',
            //                         html: 'Sedang memproses data',
            //                         allowOutsideClick: false,
            //                         didOpen: () => {
            //                             Swal
            //                                 .showLoading(); // Ini yang memicu animasi loading
            //                         }
            //                     });
            //                 },
            //                 success: function(res) {
            //                     printStruk(res.setup, res.sale);
            //                     Swal.fire({
            //                         icon: 'success',
            //                         title: 'Berhasil !',
            //                         text: res.desc,
            //                         time: 2000
            //                     });

            //                     $('#keranjang').html('')
            //                     $('#bayar').val('')
            //                     $('#kembalian').val('')
            //                     $('#catatan').val('')
            //                     $('#input_diskon').val('')
            //                     $('#potongan_harga').val('')
            //                     jumlahSubTotal();
            //                     $('#nambar').focus();
            //                     $('.customer-input').val('');
            //                     $('#customer-pelanggan').val(null).trigger('change');

            //                     setTimeout(() => {
            //                         Swal.close();
            //                     }, 1500);
            //                 },
            //                 error: function(err) {
            //                     Swal.close();
            //                     Swal.fire({
            //                         icon: 'error',
            //                         title: 'Gagal !',
            //                         text: err.responseJSON.message
            //                     })
            //                 },
            //                 complete: function() {

            //                 }
            //             })
            //         }
            //         return false;
            //     });
            // }

            // function savePrint(obj) {
            //     // obj.preventDefault();
            //     let bayar = parseInt($('#bayar').val().replace(',', ''));

            //     let jumtot = $('#jumtotInput').val();

            //     if (bayar < jumtot) {

            //         Swal.fire({
            //             icon: 'warning',
            //             title: 'Perhatian !',
            //             text: 'Bayar kurang dari jumlah total bayar'
            //         })
            //         return false;
            //     } else if (jumtot == 0 || jumtot == '') {
            //         Swal.fire({
            //             icon: 'warning',
            //             title: 'Perhatian !',
            //             text: 'Jumlah total kosong'
            //         })
            //         return false;
            //     }

            //     Swal.fire({
            //         title: "<h5>Kamu yakin ingin melanjutkan proses ini ?</h5>",
            //         showCancelButton: true,
            //         confirmButtonText: "Ya",
            //     }).then((result) => {
            //         if (result.isConfirmed) {
            //             let formData = $('#form-keranjang').serialize();
            //             $.ajax({
            //                 url: "{{ route('sale.store') }}",
            //                 type: 'POST',
            //                 data: formData,
            //                 beforeSend: function() {
            //                     $('#btn-save').attr('disabled', true);
            //                     Swal.fire({
            //                         title: 'Mohon Tunggu...',
            //                         html: 'Sedang memproses data',
            //                         allowOutsideClick: false,
            //                         didOpen: () => {
            //                             Swal
            //                                 .showLoading(); // Ini yang memicu animasi loading
            //                         }
            //                     });
            //                 },
            //                 success: function(res) {
            //                     printStruk(res.setup, res.sale);
            //                     Swal.fire({
            //                         icon: 'success',
            //                         title: 'Berhasil !',
            //                         text: res.desc,
            //                         time: 2000
            //                     });

            //                     $('#keranjang').html('')
            //                     $('#bayar').val('')
            //                     $('#kembalian').val('')
            //                     $('#catatan').val('')
            //                     $('#input_diskon').val('')
            //                     $('#potongan_harga').val('')
            //                     jumlahSubTotal();
            //                     $('#nambar').focus();
            //                     $('.customer-input').val('');
            //                     $('#customer-pelanggan').val(null).trigger('change');

            //                     setTimeout(() => {
            //                         Swal.close();
            //                     }, 1500);
            //                 },
            //                 error: function(err) {
            //                     Swal.close();
            //                     Swal.fire({
            //                         icon: 'error',
            //                         title: 'Gagal !',
            //                         text: err.responseJSON.message
            //                     })
            //                 },
            //                 complete: function() {

            //                 }
            //             })
            //         }
            //         return false;
            //     });
            // }

            // function printStruk(setup, sale) {
            //     console.log(setup, sale)
            //     const config = qz.configs.create(setup.name_printer);
            //     const getEksLogo = setup.logo.split('.').pop().toLowerCase();
            //     const getEksQris = setup.qris_image.split('.').pop().toLowerCase();
            //     const urlLogo = "{{ asset('storage/:src') }}".replace(':src', setup.logo);
            //     const urlQris = "{{ asset('storage/:src') }}".replace(':src', setup.qris_image);
            //     let data = [];

            //     // INIT
            //     data.push('\x1B\x40');

            //     // CENTER
            //     data.push('\x1B\x61\x01');

            //     data.push(setup.store_name + "\n");

            //     if (setup.show_address) {

            //         data.push(setup.address + ' - ' + setup.city + "\n");
            //     }

            //     if (setup.show_email) {

            //         data.push("Email : " + setup.email + "\n");
            //     }

            //     if (setup.show_phone) {

            //         data.push("Telp : " + setup.phone + "\n");
            //     }

            //     if (setup.show_logo) {

            //     }

            //     data.push("\n================================\n");

            //     // LEFT
            //     data.push('\x1B\x61\x00');

            //     data.push("Nama Pelanggan :  " + sale.name_customer + "\n");
            //     data.push("Tanggal        : " + sale.created_at + "\n");
            //     data.push("Kasir          :  {{ Auth::user()->name }}\n");
            //     data.push("No. Inv        : " + sale.code_sale + "\n");

            //     data.push("--------------------------------\n");

            //     data.push("Barang      Satuan      Qty   Total\n");
            //     data.push("--------------------------------\n");

            //     $.map(sale.sale_detail, function(v, i) {
            //         data.push(
            //             v.product.name.substring(0, 16) +
            //             "   " + $.number(v.unit_price) +
            //             "   " + v.quantity +
            //             "   " + $.number(v.price)
            //         )
            //     })

            //     data.push("\n");

            //     data.push("Sub Total : " + $.number(sale.sub_total) + "\n");
            //     data.push("Disc      : " + sale.diskon + "%\n");
            //     data.push("Potongan  : " + $.number(sale.diskon_price) + "\n");
            //     data.push("Total     : " + $.number(sale.total) + "\n");
            //     data.push("Bayar     : " + $.number(sale.cash_received) + "\n");
            //     data.push("Kembali   : " + $.number(sale.cash_change) + "\n");

            //     data.push("--------------------------------\n");

            //     data.push("catatan : * \n")
            //     data.push(sale.information + "\n")


            //     data.push("\n\n");

            //     // CUT
            //     data.push('\x1D\x56\x41');

            //     qz.print(config, data);

            // }
            function formatTanggal(isoString) {
                let date = new Date(isoString);

                let y = date.getFullYear();
                let m = ("0" + (date.getMonth() + 1)).slice(-2); // Bulan dimulai dari 0
                let d = ("0" + date.getDate()).slice(-2);
                let h = ("0" + date.getHours()).slice(-2);
                let i = ("0" + date.getMinutes()).slice(-2);

                return `${y}-${m}-${d} ${h}:${i}`;
            }

            async function printStruk(setup, sale) {
                // console.log(sale)
                const config = qz.configs.create(setup.name_printer, {
                    // encoding: 'ISO-8859-1',
                    language: 'ESCPOS'
                });


                // qz.print(config, [
                //     '\x1B\x40',
                //     'TEST PRINT\n',
                //     '\n\n\n',
                //     '\x1D\x56\x41'
                // ]);
                // return false;
                const urlLogo = "{{ asset('storage/:src') }}".replace(':src', setup.logo);
                const urlQris = "{{ asset('storage/:src') }}".replace(':src', setup.qris_image);
                // const getEksLogo = setup.logo.split('.').pop().toLowerCase();
                // const getEksQris = setup.qris_image.split('.').pop().toLowerCase();
                //
                let data = [];

                // INIT
                data.push('\x1B\x40');

                // CENTER
                data.push('\x1B\x61\x01');

                /*
                =========================
                LOGO
                =========================
                */


                data.push(setup.store_name + "\n");

                if (setup.show_address) {
                    data.push(setup.address + " - " + setup.city + "\n");
                }

                if (setup.show_email) {
                    data.push("Email : " + setup.email + "\n");
                }

                if (setup.show_phone) {
                    data.push("Telp : " + setup.phone + "\n");
                }
                if (setup.show_logo && setup.logo) {

                    const logoBase64 = await getBase64(urlLogo);
                    // Cek apakah base64 berhasil didapatkan
                    if (logoBase64) {
                        data.push({
                            type: 'raw',
                            format: 'image',
                            flavor: 'base64',
                            data: logoBase64,
                            options: {
                                language: "ESCPOS",
                                // dotDensity: 'double',
                                // miterLimit: 0,
                                // fallback: false,
                                // Beberapa printer lebih stabil dengan raster
                                raster: true
                            }
                        });
                        // data.push("\n");
                    } else {
                        console.warn("Logo gagal dimuat, melewati cetak logo.");
                    }

                }
                data.push("\n================================\n");

                /*
                =========================
                INFO TRANSAKSI
                =========================
                */

                data.push('\x1B\x61\x00');

                data.push("Nama Pelanggan : " + sale.name_customer + "\n");
                data.push("Tanggal        : " + formatTanggal(sale.created_at) + "\n");
                data.push("Kasir          : {{ Auth::user()->name }}\n");
                data.push("No. Inv        : " + sale.code_sale + "\n");

                data.push("--------------------------------------------\n");

                data.push("Barang               Satuan      Qty    Total\n");
                data.push("--------------------------------------------\n");

                /*
                =========================
                LIST BARANG
                =========================
                */

                sale.sale_detail.forEach(item => {

                    const name = item.product.name.substring(0, 16).padEnd(16);
                    const unit_price = $.number(item.unit_price).padStart(10);
                    const qty = item.quantity.toString().padStart(5);
                    const total = $.number(item.price).padStart(10);

                    data.push(`${name} ${unit_price} ${qty} ${total}\n`);

                });

                data.push("\n");

                /*
                =========================
                TOTAL
                =========================
                */
                let diskon = sale.diskon ?? 0;
                data.push("Sub Total                          : " + $.number(sale.sub_total) + "\n");
                data.push("Disc                               : " + diskon + "% \n");
                data.push("Potongan                           : " + $.number(sale.diskon_price) + "\n");
                data.push("Total                              : " + $.number(sale.total) + "\n");
                data.push("Bayar                              : " + $.number(sale.cash_received) + "\n");
                data.push("Kembali                            : " + $.number(sale.cash_change) + "\n");

                data.push("--------------------------------------------\n");

                /*
                =========================
                CATATAN
                =========================
                */

                if (sale.information) {
                    data.push("Catatan :\n");
                    data.push(sale.information + "\n\n");
                }
                data.push('\x1B\x61\x01');

                data.push(setup.footer_note + "\n\n");
                data.push(setup.footer_message + "\n");

                if (setup.show_qris && setup.qris_image) {

                    const qrisBase64 = await getBase64(urlQris);
                    // Cek apakah base64 berhasil didapatkan
                    if (qrisBase64) {
                        data.push({
                            type: 'raw',
                            format: 'image',
                            flavor: 'base64',
                            data: qrisBase64,
                            options: {
                                language: "ESCPOS",
                                dotDensity: 'double'

                            }
                        });
                        // data.push("\n");
                    } else {
                        console.warn("Logo gagal dimuat, melewati cetak logo.");
                    }

                }
                data.push('------')

                data.push('\x1D\x56\x41');
                qz.print(config, data);
                /*
                =========================
                QRIS
                =========================
                */


                // CUT
                // data.push('\x1D\x56\x41');

            }

            // async function getBase64(url) {

            //     const response = await fetch(url);
            //     const blob = await response.blob();

            //     return new Promise(resolve => {

            //         const reader = new FileReader();

            //         reader.onloadend = function() {
            //             resolve(reader.result.split(',')[1]);
            //         };

            //         reader.readAsDataURL(blob);

            //     });

            // }

            async function getBase64(url) {
                try {
                    const response = await fetch(url);
                    if (!response.ok) throw new Error("Gagal mengambil file");
                    const blob = await response.blob();
                    return new Promise((resolve, reject) => {
                        const reader = new FileReader();
                        reader.onloadend = () => {
                            const base64String = reader.result.split(',')[1];
                            resolve(base64String);
                        };
                        reader.onerror = reject;
                        reader.readAsDataURL(blob);
                    });
                } catch (e) {
                    console.error("Error logo:", e);
                    return null; // Kembalikan null agar bisa divalidasi
                }
            }

            function connectQZ() {
                if (qz.websocket.isActive()) {
                    return Promise.resolve();
                }

                // return qz.websocket.connect({
                //     host: window.location.hostname,
                //     port: 8181,
                //     usingSecure: false,
                //     retries: 5,
                //     delay: 1
                // });
                // return qz.websocket.connect({
                //     host: "localhost",
                //     port: 8181,
                //     usingSecure: false
                // });
                return qz.websocket.connect(); // TANPA config
            }

            function handleConnectionError(err) {
                updateState('Error', 'danger');

                if (err.target != undefined) {
                    if (err.target.readyState >= 2) { //if CLOSING or CLOSED
                        displayError("Connection to QZ Tray was closed");
                    } else {
                        displayError("A connection error occurred, check log for details");
                        console.error(err);
                    }
                } else {
                    displayError(err);
                }
            }

            // Toggle Checkbox
            $('.form-check-input').on('click', function() {
                console.log($(this).is(':checked'));
            });
        </script>
    @endsection
@endsection
