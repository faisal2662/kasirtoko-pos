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

    <!-- End Vertically centered Modal-->
@endsection

@section('script')
    <script src="{{ asset('js/jquery.number.min.js') }}"></script>

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
                    $('#tanggal_transaksi').text(res.data.created_date);
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
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil !',
                                text: res.desc,
                                time: 2000
                            });
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
            })


        }
    </script>
@endsection
