@extends('dashboard.layouts.main')

@section('title')
    Invoice | Kasir
@endsection
@section('container')
    <h3>Invoice</h3>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item active">Invoice</li>
        </ol>
    </nav>



    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mt-3 justify-content-end">
                            <div class="col-3">
                                {{--
                                <a href="{{ route('product.add') }}" class="float-end btn btn-sm btn-primary mb-2">Tambah
                                    Data</a> --}}
                            </div>
                        </div>
                        <h5 class="card-title">Invoice </h5>
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <!-- Table with stripped rows -->
                        <div class="table-responsive">

                            <table class="table " id="table-invoice">
                                <thead>
                                    <tr>
                                        <th scope="col">No.</th>
                                        <th scope="col">Nomor Invoice</th>
                                        <th scope="col">Keterangan</th>
                                        <th scope="col">Nama Pembeli</th>
                                        <th scope="col">Jenis Pembayaran</th>
                                        <th scope="col">Harga Total</th>

                                        <th scope="col" width="150">Aksi</th>
                                    </tr>
                                </thead>

                            </table>
                        </div>
                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- modal delete --}}


    <div class="modal" id="showPreview" tabindex="-1">
        <div class="modal-dialog  modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">Preview <i style="display: none;" id="load"
                            class="bx bx-refresh-cw-alt bx-spin"></i> </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-center" font-size="15px"> {{ $setup->store_name }} </p>
                    <p class="text-center" font-size="13px">
                        {{ $setup->show_address == 1 ? $setup->address . ' - ' . $setup->city : '' }} </p>
                    <p class="text-center" font-size="13px">
                        {{ $setup->show_email == 1 ? 'Email : ' . $setup->email : '' }} </p>
                    <p class="text-center" font-size="13px">
                        {{ $setup->show_phone == 1 ? 'Telp : ' . $setup->phone : '' }} </p>

                    @if ($setup->show_logo)
                        <p class="text-center">
                            <image height="100px" style="text-align:center;" src="{{ asset('storage/' . $setup->logo) }}">
                        </p>
                    @endif
                    <p class="text-center">

                        ===================================================
                    </p>
                    <div class="row mb-3">
                        <div class="col-4">Nama Customer </div>
                        <div class="col-5">: <span id="nama_customer"></span> </div>
                    </div>
                    <div class="row">
                        <div class="col-4">Tanggal </div>
                        <div class="col-5">: <span id="tanggal_transaksi"></span> </div>
                    </div>
                    <div class="row">
                        <div class="col-4">Kasir </div>
                        <div class="col-5">: <span id="kasir"></span></div>
                    </div>
                    <div class="row">
                        <div class="col-4">No. Invoice </div>
                        <div class="col-5">: <span id="invoice"></span> </div>
                    </div>

                    <p class="text-center">

                        --------------------------------------------------------------------------------
                    </p>
                    <table>
                        <thead>
                            <tr>
                                <td width="200">Barang</td>
                                <td width="100">Satuan</td>
                                <td width="100">Qty</td>
                                <td width="100">Total</td>
                            </tr>
                        </thead>
                        <tr>
                            <td colspan="4" class="text-center">
                                --------------------------------------------------------------------------</td>
                        </tr>
                        <tbody id="body-detail">




                        </tbody>
                        <tr>
                            <td colspan="4" class="text-center">
                                ----------------------------------------------------------------------------</td>
                        </tr>
                        <tbody style="text-align:left;">
                            <tr>
                                <td colspan="3">Sub Total</td>
                                <td> <span class="rupiah" id="sub_total"></span> </td>
                            </tr>
                            <tr>
                                <td colspan="3">Diskon %</td>
                                <td> <span id="diskon"></span> </td>
                            </tr>
                            <tr>
                                <td colspan="3">Potongan Harga</td>
                                <td> <span id="pothar" class="rupiah"></span> </td>
                            </tr>
                            <tr>
                                <td colspan="3">Total</td>
                                <td> <span id="total" class="rupiah"></span> </td>
                            </tr>
                            <tr>
                                <td colspan="4"></td>
                            </tr>
                            <tr>
                                <td colspan="3">Bayar</td>
                                <td> <span id="bayar" class="rupiah"></span> </td>
                            </tr>
                            <tr>
                                <td colspan="3">Kembalian</td>
                                <td> <span id="kembalian" class="rupiah"></span> </td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="text-center">--------------------------------------------------------------------------------
                    </p>
                    <p>Catatan : *</p>
                    <p id="note"></p>
                    <p class="text-center"> {{ $setup->footer_note }} </p>
                    <p class="text-center"> {{ $setup->footer_message }} </p>
                    @if ($setup->show_qris)
                        <p class="text-center">
                            <image height="100px" style="text-align:center;"
                                src="{{ asset('storage/' . $setup->qris_image) }}">
                        </p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button class="btn btn-warning" id="btn-print"><i class="bx bx-printer"></i> &nbsp; Print
                        Ulang</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>

                </div>
            </div>
        </div>
    </div>
    <iframe id="print-frame" name="print-frame" style="display:none;"></iframe>

    <!-- End Vertically centered Modal-->
@endsection

@section('script')
    <script src="{{ asset('js/jquery.number.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/qz-tray/qz-tray.js"></script>
    <script>
        var table = null;
        $(document).ready(function() {
            table = $('#table-invoice').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: "{{ route('invoice.datatable') }}",
                    type: 'GET',
                },
                columns: [{
                        data: 'no'
                    },
                    {
                        data: 'code_sale'
                    },
                    {
                        data: 'keterangan'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'payment.method'
                    },
                    {
                        data: 'total'
                    },
                    {
                        data: 'action'
                    }

                ]
            });


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
        });

        function showData(id) {
            $('#showPreview').modal('show')
            $('#load').css('display', 'inline-block')

            $.ajax({
                url: "{{ route('invoice.show', ':id') }}".replace(':id', id),
                type: 'get',
                beforeSuccess: function(res) {
                    $('#name_product').text('')
                    $('#category').text('')
                    $('#price').text('')
                    $('#stock').text('')

                },
                success: function(res) {

                    $('#customer_customer').text(res.data.customer_name)
                    $('#tanggal_transaksi').text(convertTime(res.data.created_at));
                    $('#kasir').text(res.data.kasir_name);
                    $('#invoice').text(res.data.code_sale);
                    $('#sub_total').text(res.data.sub_total);
                    $('#diskon').text(res.data.diskon);
                    $('#pothar').text(res.data.diskon_price);
                    $('#total').text(res.data.total);
                    $('#bayar').text(res.data.cash_received);
                    $('#kembalian').text(res.data.cash_change);
                    $('#note').text(res.data.information);
                    $('#btn-print').attr('onclick', 'printUlang(' + id + ')')
                    $('#body-detail').html('');
                    let baris = '';
                    $.map(res.data.sale_detail, function(v, i) {
                        baris += `
                         <tr>
                                <td> ${v.product.name.substring(0,18)} </td>
                                <td class="rupiah"> ${v.unit_price} </td>
                                <td> ${v.quantity} </td>
                                <td class="rupiah"> ${v.price} </td>
                            </tr>
                        `;
                    })
                    $('#body-detail').html(baris)
                },
                error: function(error) {
                    console.log(error)
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: "Terjadi kesalahan",
                        showConfirmButton: false,
                        timer: 1500
                    });
                },
                complete: function(res) {
                    $('#load').css('display', 'none')

                    $('.rupiah').number(true, 0)

                }
            })
        }

        function convertTime(waktu) {
            let date = new Date(waktu);

            let tahun = date.getFullYear();
            let bulan = String(date.getMonth() + 1).padStart(2, '0');
            let hari = String(date.getDate()).padStart(2, '0');
            let jam = String(date.getHours()).padStart(2, '0');
            let menit = String(date.getMinutes()).padStart(2, '0');

            let hasil = `${tahun}-${bulan}-${hari} ${jam}:${menit}`;
            return hasil;
        }

        function printUlang(id) {


            Swal.fire({
                title: "<h5>Kamu yakin ingin melanjutkan proses ini ?</h5>",
                showCancelButton: true,
                confirmButtonText: "Ya",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('invoice.prePrint') }}",
                        type: 'post',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id_sale: id
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Mohon Tunggu...',
                                html: 'Sedang Memproses data',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            })
                        },
                        success: function(res) {
                            printStruk(res.data, res.sale, res.sale.decodeLogo, res.sale.decodeqris).then(() => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil !',
                                    text: res.desc,
                                    time: 2000
                                });
                                setTimeout(() => {
                                    Swal.close();
                                }, 1500);

                            })
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
            })

        }

        function formatTanggal(isoString) {
            let date = new Date(isoString);

            let y = date.getFullYear();
            let m = ("0" + (date.getMonth() + 1)).slice(-2); // Bulan dimulai dari 0
            let d = ("0" + date.getDate()).slice(-2);
            let h = ("0" + date.getHours()).slice(-2);
            let i = ("0" + date.getMinutes()).slice(-2);

            return `${y}-${m}-${d} ${h}:${i}`;
        }

        async function printStruk(setup, sale, logo, qris) {
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

                // const logoBase64 = await getBase64(urlLogo);
                // Cek apakah base64 berhasil didapatkan
                data.push({
                    type: 'raw',
                    format: 'base64',
                    // flavor: 'base64',
                    data: logo,
                    // options: {
                    //     language: "ESCPOS",
                    //     // dotDensity: 'double',
                    //     // miterLimit: 0,
                    //     // fallback: false,
                    //     // Beberapa printer lebih stabil dengan raster
                    //     raster: true
                    // }
                });

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

                data.push({
                    type: 'raw',
                    format: 'base64',
                    // flavor: 'base64',
                    data: qris,
                    // options: {
                    //     language: "ESCPOS",
                    //     dotDensity: 'double'

                    // }
                });


            }
            data.push('------')

            data.push('\x1D\x56\x41');
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
    </script>
@endsection
