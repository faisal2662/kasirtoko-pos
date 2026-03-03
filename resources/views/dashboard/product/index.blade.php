@extends('dashboard.layouts.main')

@section('title')
    Kasir | Product
@endsection
@section('container')
    <h3>Barang</h3>
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
                        <div class="row mt-3 justify-content-end">
                            <div class="col-3">

                                <a href="{{ route('product.add') }}" class="float-end btn btn-sm btn-primary mb-2">Tambah
                                    Data</a>
                            </div>
                        </div>
                        <h5 class="card-title">Data Barang</h5>
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <!-- Table with stripped rows -->
                        <div class="table-responsive">

                            <table class="table " id="table-product">
                                <thead>
                                    <tr>
                                        <th scope="col">No.</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Kategory</th>
                                        <th scope="col">Harga Beli </th>
                                        <th scope="col">Harga Jual </th>
                                        <th scope="col">Satuan</th>
                                        <th scope="col">Stok</th>
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

    <div class="modal" id="showProduct" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">Detail Data <i style="display: none;" id="load"
                            class="bx bx-refresh-cw-alt bx-spin"></i> </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-4 fw-bold">Nama Barang</div>
                        <div class="col-8"><span id="name_product"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 fw-bold">Kategori</div>
                        <div class="col-8"><span id="category"></span></div>
                    </div>
                    <div class="row  mb-2">
                        <div class="col-4 fw-bold">Satuan Pembelian</div>
                        <div class="col-8"><span id="satuan_dasar"></span></div>
                    </div>
                    {{-- <div class="row  mb-2">
                        <div class="col-4 fw-bold">Satuan Penjualan</div>
                        <div class="col-8"><span id="purchase_unit_id"></span></div>
                    </div> --}}
                    <div class="row  mb-2">
                        <div class="col-4 fw-bold">Isi Per Pembelian</div>
                        <div class="col-8"><span id="content_per_unit"></span></div>
                    </div>
                    <div class="row  mb-2">
                        <div class="col-4 fw-bold">Harga Beli</div>
                        <div class="col-8"><span id="purchase_price" class="rupiah"></span></div>
                    </div>
                    <div class="row  mb-2">
                        <div class="col-4 fw-bold">Harga Jual</div>
                        <div class="col-8"><span id="selling_price" class="rupiah"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 fw-bold">Stok</div>
                        <div class="col-8"><span id="stock"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 fw-bold">Minimal Stock</div>
                        <div class="col-8"><span id="min_stock"></span></div>
                    </div>
                </div>
                <div class="modal-footer">
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
            table = $('#table-product').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: "{{ route('product.datatable') }}",
                    type: 'GET',
                },
                columns: [{
                        data: 'no'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'categories_name'
                    },
                    {
                        data: 'purchase_price'
                    },
                    {
                        data: 'selling_price'
                    },
                    {
                        data: 'short'
                    },
                    {
                        data: 'stock'
                    },
                    {
                        data: 'action'
                    }

                ]
            });
        });

        function showData(id) {
            $('#showProduct').modal('show')
            $('#load').css('display', 'inline-block')

            $.ajax({
                url: "{{ route('product.show', ':id') }}".replace(':id', id),
                type: 'get',
                beforeSuccess: function(res) {
                    $('#name_product').text('')
                    $('#category').text('')
                    $('#price').text('')
                    $('#stock').text('')

                },
                success: function(res) {

                    $('#name_product').text(res.data.name)
                    $('#category').text(res.data.category_name)
                    $('#satuan_dasar').text(res.data.unit_name)
                    $('#content_per_unit').text(res.data.content_per_unit + ' / ' + res.data.purchase_unit_name)
                    $('#purchase_price').text(res.data.purchase_price)
                    $('#selling_price').text(res.data.selling_price)
                    $('#stock').text(res.data.stock)
                    $('#min_stock').text(res.data.min_stock)
                    $('#load').css('display', 'none')
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

        function deleteData(id) {
            Swal.fire({
                title: "<h5>Kamu yakin ingin menghapus ini ? </h5>",
                showCancelButton: true,
                confirmButtonText: "Ya",
                confirmButtonColor: "#311dea",
                cancelButtonText: "Tidak",
                cancelButtonColor: "#ea1d1d"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('product.destroy', ':id') }}".replace(':id', id),
                        type: 'get',
                        success: function(res) {
                            Swal.fire({
                                position: "top-end",
                                icon: "success",
                                title: res.desc,
                                showConfirmButton: false,
                                timer: 1500
                            })
                            table.ajax.reload()
                        },
                        error: function(err) {
                            Swal.fire({
                                position: "top-end",
                                icon: "error",
                                title: err.desc,
                                showConfirmButton: false,
                                timer: 1500
                            })
                            table.ajax.reload()

                        }
                    })
                }
            });
        }
    </script>
@endsection
