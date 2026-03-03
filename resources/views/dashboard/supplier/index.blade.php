@extends('dashboard.layouts.main')

@section('title')
    Kasir | Supplier
@endsection
@section('container')
    <h3>Supplier</h3>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item active">Supplier</li>
        </ol>
    </nav>



    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mt-3 justify-content-end">
                            <div class="col-3">

                                <a href="#" onclick="addForm()" class="float-end btn btn-sm btn-primary mb-2">Tambah
                                    Data</a>
                            </div>
                        </div>
                        <h5 class="card-title">Data Barang</h5>

                        <!-- Table with stripped rows -->
                        <div class="table-responsive">

                            <table class="table " id="table-product">
                                <thead>
                                    <tr>
                                        <th scope="col">No.</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Nomor</th>
                                        <th scope="col">Alamat </th>

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

    <div class="modal" id="modalSupplier" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"> <span id="title-form"></span> <i style="display: none;"
                            id="load" class="bx bx-refresh-cw-alt bx-spin"></i> </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" id="formSupplier">
                    @csrf
                    <input type="hidden" name="id_supplier" id="id_supplier">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="" class="form-label">Nama <span class="text-danger">*</span> </label>
                            <input type="text" class="form-control" id="name-form" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Nomor Telpon
                            </label>
                            <input type="number" class="form-control" id="phone-form" name="phone">
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Alamat</label>
                            <textarea name="address" id="address-form" cols="30" rows="3" class="form-control"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button class="btn btn-sm btn-primary btn-save">Simpan</button>
                    </div>
                </form>
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
                    url: "{{ route('supplier.datatable') }}",
                    type: 'GET',
                },
                columns: [{
                        data: 'no'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'phone'
                    },
                    {
                        data: 'address'
                    },
                    {
                        data: 'action'
                    }
                ]
            });
        });

        function reset() {
            // $('input.form-control').val('');
            // $('#adress-form').html('')
            $('input.form-control, textarea.form-control').val('');
        }
        let form = null;

        function addForm() {
            $('#modalSupplier').modal('show');
            reset();
            form = 'add';
            $('#title-form').text('Tambah Data')

        }


        function deleteDataSupplier(id) {
            Swal.fire({
                text: "Kamu yakin ingin menghapus data ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('supplier.destroy', ':id') }}".replace(':id', id),
                        type: 'POST',
                        data:{
                            _token : "{{ csrf_token() }}"
                        },
                        success: function(res) {
                            Swal.fire({
                                position: "top-end",
                                icon: "success",
                                title: res.desc,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            table.ajax.reload();
                        },
                        error: function(err) {
                            Swal.fire({
                                position: "top-end",
                                icon: "warning",
                                title: err.responseJSON.message,
                                showConfirmButton: false,
                                timer: 1500
                            })

                        }
                    })

                }
            });
        }

        function updateData(id) {
            $.ajax({
                url: "{{ route('supplier.show', ':id') }}".replace(':id', id),
                type: 'get',
                beforeSend: function() {
                    $('#load').show();
                    reset();
                },
                success: function(res) {

                    let data = res.data;
                    $('#id_supplier').val(data.id);
                    $('#name-form').val(data.name);
                    $('#phone-form').val(data.phone);
                    $('#address-form').val(data.address);
                },
                error: function(err) {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: err.responseJSON.message,
                        showConfirmButton: false,
                        timer: 1500
                    })
                },
                complete: function() {
                    $('#load').hide()
                    $('#modalSupplier').modal('show')

                    form = 'update';
                    $('#title-form').text('Ubah Data')
                }
            })
        }

        $('#formSupplier').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            console.log(formData)
            if (form == 'add') {
                $.ajax({
                    url: "{{ route('supplier.store') }}",
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        $('#load').show();
                        $('.btn-save').attr('disabled', true)
                    },
                    success: function(res) {
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: res.desc,
                            showConfirmButton: false,
                            timer: 1500
                        })
                    },
                    error: function(err) {
                        Swal.fire({
                            position: "top-end",
                            icon: "warning",
                            title: err.responseJSON.message,
                            showConfirmButton: false,
                            timer: 1500
                        })
                    },
                    complete: function() {
                        $('#load').hide()
                        $('.btn-save').attr('disabled', false);
                        reset();
                        $('#modalSupplier').modal('hide')
                        table.ajax.reload()
                    }
                })
            } else {
                $.ajax({
                    url: "{{ route('supplier.update', ':id') }}".replace(':id', $('#id_supplier').val()),
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        $('#load').show();
                        $('.btn-save').attr('disabled', true)
                    },
                    success: function(res) {
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: res.desc,
                            showConfirmButton: false,
                            timer: 1500
                        })
                    },
                    error: function(err) {
                        Swal.fire({
                            position: "top-end",
                            icon: "warning",
                            title: err.responseJSON.message,
                            showConfirmButton: false,
                            timer: 1500
                        })
                    },
                    complete: function() {
                        $('#load').hide()
                        $('.btn-save').attr('disabled', false)
                        $('#modalSupplier').modal('hide')
                        table.ajax.reload()
                    }
                })
            }
        })


    </script>
@endsection
