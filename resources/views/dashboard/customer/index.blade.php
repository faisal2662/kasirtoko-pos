@extends('dashboard.layouts.main')
@section('title')
    Kasir | Customer
@endsection
@section('container')
    <h1>Pelanggan</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Pelanggan</li>
        </ol>
    </nav>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <a href="#" onclick="addCustomer()" class="btn btn-primary mb-2 mt-4 float-end">Tambah Data</a>
                        <h5 class="card-title mb-3">Data Pelanggan</h5>

                        <!-- Table with stripped rows -->
                        <table class="table mt-4" id='table-customer'>
                            <thead>
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Jenis Kelamin</th>
                                    <th scope="col">No. Telp</th>
                                    <th scope="col">Alamat</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @foreach ($customers as $customer)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ $customer->gender }}</td>
                                        <td>{{ $customer->phone }}</td>
                                        <td>{{ $customer->address }}</td>
                                        <td><a href="customer/edit/{{ $customer->code_customer }}"
                                                class="btn btn-warning">Ubah</a>
                                            || <a onclick="confirm('ya')"
                                                href="/dashboard/customer-delete/{{ $customer->code_customer }}"
                                                class="btn btn-danger">Hapus</a></td>
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

    {{-- modal --}}
    <div class="modal fade" id="modalCustomer" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span id="title-customer"></span>
                    </h5>
                    <span style="margin-left: 30px;display:none;" id="load-show"><i
                            class="bx bx-refresh-cw-alt bx-spin"></i></span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                        <form id="formCustomer" method="post" >
                        @csrf
                        <input type="hidden" name="id_customer" id="id_customer">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama <span class="text-danger">*</span> </label>
                            <input type="text" name="name" id="name" class="form-control" required autofocus
                                autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="short" class="form-label">Role</label>
                            <select name="role_id" id="role_customer" class="form-control">
                                <option value="" disabled>-- Pilih Role --</option>
                                @foreach ($roleCustomer as $item)
                                    <option value="{{ $item->id }}"> {{ $item->name }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" id="gender" value="laki-laki" type="radio"
                                    name="gender">
                                <label class="form-check-label" for="radioDefault1">
                                    Laki - Laki
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" id="gender" type="radio" value="perempuan"
                                    name="gender">
                                <label class="form-check-label" for="radioDefault2">
                                    Perempuan
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">No. Telpon</label>
                            <input type="number" name="phone" id="phone" class="form-control">

                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Alamat</label>
                            <textarea rows="4" class="form-control" name="address" id="address"></textarea>
                        </div>

                    </div>
                </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary btn-save-customer">Simpan </button>
                    </div>
            </div>
        </div>
    </div>

@section('script')
    <script>
        var table = null;
        $(document).ready(function() {
            table = $('#table-customer').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: "{{ route('customer.datatable') }}",
                    type: 'GET',
                },
                columns: [{
                        data: 'no'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'role_customers_name'
                    },
                    {
                        data: 'gender'
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

        let form = null;

        function addCustomer() {
            $('#modalCustomer').modal('show');
            $('#title-customer').text('Tambah Customer');
            $('input.form-control').val('')
            $('textarea.form-control').val('')
            form = 'add';
        }


        function updateCustomer(id) {
            $('#modalCustomer').modal('show');
            $('#title-customer').text('Update Customer');
            form = 'update';
            $.ajax({
                url: "{{ route('customer.edit', ':id') }}".replace(':id', id),
                type: 'GET',
                data: {
                    id_customer: id
                },
                beforeSend: function() {
                    $('#load-show').show()
                },
                success: function(res) {
                    let data = res.data;
                    $('#id_customer').val(data.id)
                    $('#name').val(data.name)
                    $('#role_customer').val(data.role_id);
                    // $('#role_id option[value=' + data.role_id + ']').prop('selected', true);
                    $('#gender[value=' + data.gender + ']').attr('checked', true);
                    $('#phone').val(data.phone);
                    $('#address').val(data.address)
                },
                error: function(err) {
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: err.responseJSON.message,
                        showConfirmButton: false,
                        timer: 1500
                    })
                },
                complete: function() {
                    $('#load-show').hide()
                }
            })
        }

        $('.btn-save-customer').on('click', function(e) {
            e.preventDefault();

            let formData = $('#formCustomer').serialize();
            if (form == 'add') {
                $.ajax({
                    url: "{{ route('customer.store') }}",
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Mohon Tunggu...',
                            html: 'Sedang memproses data',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading(); // Ini yang memicu animasi loading
                            }
                        });
                    },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.desc,
                            timer: 2000 // Otomatis tutup dalam 2 detik
                        });
                        $('input.form-control').val('')
                        $('textarea.form-control').val('')
                        setTimeout(() => {

                            Swal.close();
                        }, 1800);
                    },
                    error: function(err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan: ' + err.responseJSON.message
                        });
                    },
                    complete: function() {
                        $('#modalCustomer').modal('hide');
                        table.ajax.reload()

                    }
                })
            } else {
                let id_customer = $('#id_customer').val();
                $.ajax({
                    url: "{{ route('customer.update', ':id') }}".replace(':id', id_customer),
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Mohon Tunggu...',
                            html: 'Sedang memproses data',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading(); // Ini yang memicu animasi loading
                            }
                        });
                    },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.desc,
                            timer: 2000 // Otomatis tutup dalam 2 detik
                        });
                        $('input.form-control').val('')
                        $('textarea.form-control').val('')
                        setTimeout(() => {

                            Swal.close();
                        }, 1800);
                    },
                    error: function(err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan: ' + err.responseJSON.message
                        });
                    },
                    complete: function() {
                        $('#modalCustomer').modal('hide');
                        table.ajax.reload()

                    }
                })
            }
        })





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
                        url: "{{ route('customer.destroy', ':id') }}".replace(':id', id),
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
@endsection
