@extends('dashboard.layouts.main')

@section('title')
    Menu | Kasir
@endsection
@section('container')
    <h3>Menu</h3>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Menu</li>
        </ol>
    </nav>



    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mt-3 justify-content-end">
                            <div class="col-3">

                                <a onclick="formAdd()" class="float-end btn btn-sm btn-primary mb-2">Tambah
                                    Data</a>
                            </div>
                        </div>
                        <h5 class="card-title">Menu </h5>
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <!-- Table with stripped rows -->
                   <table class="table">
                    <thead>
                        <tr>
                            <td>No.</td>
                            <td>Role</td>
                            <td>Aksi</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($role as $item)

                        <tr>
                            <td> {{ $loop->iteration }} </td>
                            <td> {{ $item->name }} </td>
                            <td>  <a href="{{ route('menu.role_menu.config', $item->id) }}" class="badge bg-warning" ><i class="bi bi-pencil-square"></i> Configurasi</a>    </td>
                        </tr>
                        @endforeach
                    </tbody>
                   </table>
                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- Modal -->
    <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"> <span id="titleModal"></span> </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" id="formMenu">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="id_menu" id="id_menu">
                        <div class="mb-3">
                            <label for="" class="form-label">Title</label>
                            <select name="parent" id="parent" class="form-control">
                                <option value=""> -- Tidak Memilih -- </option>
                                {{-- @foreach ($menus as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $menu->parent_id == $item->id ? 'selected' : '' }}> {{ $item->name }}
                                    </option>
                                    @if ($item->children->count())
                                        @foreach ($item->children as $child)
                                            <option value="{{ $child->id }}">-- {{ $child->name }} </option>
                                        @endforeach
                                    @endif
                                @endforeach --}}
                            </select>
                            {{-- <input type="text" id="title" class="form-control" name="title"> --}}
                        </div>
                        <div class="mb-2">
                            <label for="" class="form-label">Nama Menu</label>
                            <input type="text" id="name" class="form-control" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Route</label>
                            <input type="text" id="route" class="form-control" name="route">
                        </div>
                        <label for="" class="form-label">Header</label>
                        <div class="mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input menu_header" type="radio" name="header" value="0"
                                    id="radioDefault1" checked>
                                <label class="form-check-label" for="radioDefault1">
                                    N
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input menu_header" type="radio" name="header" value="1"
                                    id="radioDefault2">
                                <label class="form-check-label" for="radioDefault2">
                                    Y
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Icon</label>
                            <input type="text" id="icon" class="form-control" name="icon">
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" id="btn-save" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Vertically centered Modal-->
@endsection

@section('script')
    <script src="{{ asset('js/jquery.number.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

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
        let form = null;

        function clear() {
            $('#title').val('');
            $('#name').val('');
            $('#route').val('');
            $('#icon').val('');
        }

        function formAdd() {
            $('#titleModal').text('Tambah Data');
            clear();
            $('#formModal').modal('show')
            form = 'create';
        }

        function formUpdate(id, menu, icon, route, header, parent_id) {
            clear(); // Pastikan fungsi ini membersihkan form input lainnya
            $('#formModal').modal('show');
            $('#titleModal').text('Ubah Data');

            $('#id_menu').val(id)

            // 1. Set Dropdown Parent
            // Gunakan .val() karena lebih simpel dan otomatis menangani 'selected'
            $('#parent').val(parent_id);

            // 2. Set Input Text
            $('#name').val(menu);
            $('#route').val(route);
            $('#icon').val(icon);

            // 3. Set Radio/Checkbox Header
            // Hapus semua centang dulu agar tidak dobel (reset state)
            // $('.menu_header').prop('checked', false);
            $('.menu_header').removeAttr('checked');
            // Centang elemen yang memiliki value sesuai variabel header
            $('.menu_header').filter('[value="' + header + '"]').prop('checked', true);
            form = 'update';

        }


        $('#formMenu').on('submit', function() {
            event.preventDefault();
            let formData = $(this).serialize();

            if (form == 'create') {
                $.ajax({
                    url: "{{ route('menu.store') }}",
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        $('#btn-save').attr('disabled', true)
                    },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.desc,
                            time: 2000,

                        }).then(() => {
                            location.reload()
                        }, 500);

                    },
                    error: function(err) {
                        // Notiflix.Notify.failure(err.responseJSON.message)
                        Swal.fire({
                            icon: 'error',
                            title: "Gagal",
                            text: err.responseJSON.message
                        })

                    },
                    complete: function() {
                        $('#btn-save').attr('disabled', false)
                    }
                })
            } else {
                $.ajax({
                    url: "{{ route('menu.update') }}",
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        $('#btn-save').attr('disabled', true)
                    },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.desc,
                            time: 2000,

                        }).then(() => {
                            location.reload()
                        }, 500);

                    },
                    error: function(err) {
                        Swal.fire({
                            icon: 'error',
                            title: "Gagal",
                            text: err.responseJSON.message
                        })
                    },
                    complete: function() {
                        $('#btn-save').attr('disabled', false)
                    }
                })

            }
        })


        function hapus_menu(id) {
            Swal.fire({
                title: "Kamu yakin ingin menghapus ini?",

                showCancelButton: true,
                confirmButtonText: "Ya",
                denyButtonText: `Tidak`
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('menu.destroy') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Mohon Tunggu...',
                                html: 'Sedang memproses data',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal
                                        .showLoading(); // Ini yang memicu animasi loading
                                }
                            }).then(() => {
                                location.reload()
                            });
                        },
                        success: function(res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: res.desc,
                                time: 2000
                            });
                        },
                        error: function(err) {
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal !',
                                text: err.responseJSON.message
                            })
                        }
                    })

                }
            });
        }

        let menuSortable = new Sortable(
            document.getElementById('menuSortable'), {
                animation: 150
            }
        );

        // submenu
        document.querySelectorAll('.submenu-sortable').forEach(el => {
            new Sortable(el, {
                animation: 150
            });
        });

        document.getElementById('saveOrder').addEventListener('click', () => {

            let data = [];

            document.querySelectorAll('#menuSortable > li').forEach((menu, index) => {
                data.push({
                    id: menu.dataset.id,
                    parent_id: null,
                    order: index + 1
                });

                let submenu = menu.querySelectorAll('.submenu-sortable > li');
                submenu.forEach((child, cIndex) => {
                    data.push({
                        id: child.dataset.id,
                        parent_id: menu.dataset.id,
                        order: cIndex + 1
                    });
                });
            });

            fetch("{{ route('menu.saveOrder') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        menus: data
                    })
                })
                .then(res => res.json())
                .then(res => {
                    alert('Urutan menu berhasil disimpan');
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

            let url = "{{ route('print.struk', ':id') }}";

            // Ganti :id dengan ID yang didapat dari response simpan kasir
            url = url.replace(':id', id);

            // Buka di tab baru
            // window.open(url, '_blank');
            $('#print-frame').attr('src', url);
            // Swal.fire({
            //     title: "<h5>Kamu yakin ingin melanjutkan proses ini ?</h5>",
            //     showCancelButton: true,
            //     confirmButtonText: "Ya",
            // }).then((result) => {
            //     if (result.isConfirmed) {
            //         $.ajax({
            //             url: "{{ route('invoice.prePrint') }}",
            //             type: 'post',
            //             data: {
            //                 _token: '{{ csrf_token() }}',
            //                 id_sale: id
            //             },
            //             beforeSend: function() {
            //                 Swal.fire({
            //                     title: 'Mohon Tunggu...',
            //                     html: 'Sedang Memproses data',
            //                     allowOutsideClick: false,
            //                     didOpen: () => {
            //                         Swal.showLoading();
            //                     }
            //                 })
            //             },
            //             success: function(res) {
            //                 Swal.fire({
            //                     icon: 'success',
            //                     title: 'Berhasil !',
            //                     text: res.desc,
            //                     time: 2000
            //                 });
            //                 setTimeout(() => {
            //                     Swal.close();
            //                 }, 1500);
            //             },
            //             error: function(err) {
            //                 Swal.close();
            //                 Swal.fire({
            //                     icon: 'error',
            //                     title: 'Gagal !',
            //                     text: err.responseJSON.message
            //                 })
            //             },
            //             complete: function() {

            //             }
            //         })
            //     }
            //     return false;
            // })


        }
    </script>
@endsection
