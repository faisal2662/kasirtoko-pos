@extends('dashboard.layouts.main')
@section('title')
    Kasir | Category
@endsection
@section('container')
    <h3>Category</h3>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item active">Category</li>
        </ol>
    </nav>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <a href="#" onclick="addCategory()" class="btn mt-3 btn-primary mb-2 float-end">Tambah Data</a>
                        <h5 class="card-title">Data Category</h5>

                        <!-- Table with stripped rows -->
                        <table class="table " id="table-category">
                            <thead>
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @foreach ($category as $item)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $item->name }}</td>
                                        <td><a href="" data-bs-toggle="modal"
                                                data-bs-target="#editCategory{{ $item->slug }}"
                                                class="btn btn-warning">Ubah</a>
                                            || <a data-bs-toggle="modal" data-bs-target="#deleteCategory{{ $item->slug }}"
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
    <div class="modal fade" id="modalCategory" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span id="title-form-categgory"></span> <span><i
                                class="bx bx-refresh-cw-alt bx-spin" id="load-show" style="display:none;"></i></span> </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formCategory" method="post">
                        @csrf
                        <input type="hidden" name="id_category" id="id_category">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Category</label>
                            <input type="text" name="name" required id="name-category" class="form-control" autofocus
                                autofocus>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan </button>
                    </form>
                </div>
            </div>
        </div>
    </div>



@section('script')
    <script>
        var table = null;
        $(document).ready(function() {
            let form = null;
            table = $('#table-category').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: "{{ route('category.datatable') }}",
                    type: 'GET',
                },
                columns: [{
                        data: 'no'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'action'
                    }

                ]
            });
        });


        function addCategory() {
            $('#modalCategory').modal('show')
            form = 'add';
            $('#title-form-category').text('Tambah Category')

        }

        function updatedCategory(id) {
            $('#modalCategory').modal('show')
            form = 'update';
            $.ajax({
                url: "{{ route('category.edit', ':id') }}".replace(':id', id),
                type: 'GET',
                data: {
                    id_category: id,
                },
                beforeSend: function() {
                    $('#load-show').show()
                },
                success: function(res) {
                    $('#name-category').val(res.data.name);
                    $('#id_category').val(res.data.id);
                    $('#title-form-category').text('Edit Category')

                },
                error: function(err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan: ' + err.responseJSON.message
                    });
                },
                complete: function() {
                    $('#load-show').hide()
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
                        url: "{{ route('category.delete', ':id') }}".replace(':id', id),
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


        $('#formCategory').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            if (form == 'update') {
                let id_category = $("#id_category").val()
                $.ajax({
                    url: "{{ route('category.update', ':id') }}".replace(':id', id_category),
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
                        $('#name-add').val('')
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
                        $('#modalCategory').modal('hide');
                        table.ajax.reload()

                    }
                })
            } else {

                $.ajax({
                    url: "{{ route('category.add') }}",
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
                        $('#name-add').val('')
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
                        $('#modalCategory').modal('hide');
                        table.ajax.reload()
                    }
                })
            }
        })
    </script>
@endsection
@endsection
