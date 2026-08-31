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



    <div class="container">
    <h4>Setting Role Access</h4>

    {{-- SELECT ROLE --}}
    <form method="GET">
        <div class="mb-3">
            <label>Pilih Role</label>
            <select name="role_id" onchange="this.form.submit()" class="form-control">
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ $roleId == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    {{-- FORM CHECKBOX --}}
    <form method="POST" id="formUpdate" action="{{ route('menu.role_menu.update') }}">
        @csrf
        <input type="hidden" name="role_id" value="{{ $roleId }}">

        <div class="card p-3">

            @foreach($menus as $menu)
                <div class="mb-2">

                    {{-- PARENT --}}
                    <div>
                        <input type="checkbox"
                               name="menu[]"
                               value="{{ $menu->id }}"
                               class="parent-checkbox"
                               {{ in_array($menu->id, $roleMenus) ? 'checked' : '' }}>

                        <strong>{{ $menu->name }}</strong>
                    </div>

                    {{-- CHILD --}}
                    @if($menu->children->count())
                        <div style="margin-left:20px;">
                            @foreach($menu->children as $child)
                                <div>
                                    <input type="checkbox"
                                           name="menu[]"
                                           value="{{ $child->id }}"
                                           class="child-checkbox parent-{{ $menu->id }}"
                                           {{ in_array($child->id, $roleMenus) ? 'checked' : '' }}>

                                    {{ $child->name }}
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            @endforeach

        </div>

        <button class="btn btn-primary mt-3">Simpan</button>
    </form>
</div>




@endsection

@section('script')
    <script src="{{ asset('js/jquery.number.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

    <script>
        var table = null;

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


        $('#formUpdate').on('submit', function() {
            event.preventDefault();
            let formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('menu.role_menu.update') }}",
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
