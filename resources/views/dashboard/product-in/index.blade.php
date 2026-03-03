@extends('dashboard.layouts.main')
@section('title')
    Kasir | Barang Masuk
@endsection
@section('head')
    <link rel="stylesheet" href="{{ asset('select2/dist/css/select2.min.css') }}">
@endsection
@section('container')
    <style>
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
    <h3>Barang Masuk</h3>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item active">Barang Masuk</li>
        </ol>
    </nav>

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="card">
                    <div class="card-header">
                        <span class="float-end fs-4" id="load" style="display:none;"><i
                                class="bx bx-refresh-cw-alt bx-spin"></i></span>
                        <div class="card-title">Barang Masuk</div>
                    </div>
                    <div class="card-body">
                        <div class="float-end mt-3 mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="supplier" onclick="removeSupplier()"
                                    id="radioDefault1" checked>
                                <label class="form-check-label" for="radioDefault1">
                                    Non Supplier
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="supplier" onclick="addSupplier()"
                                    id="radioDefault2">
                                <label class="form-check-label" for="radioDefault2">
                                    Supplier
                                </label>
                            </div>
                        </div>
                        <form action="" class="mt-4" method="post" id="formProductIn">
                            @csrf
                            <input type="hidden" name="" id="content_per_unit">
                            <div class="mb-3">
                                <div id="container-supplier">

                                </div>
                            </div>
                            <div class="mb-3" id="container-supplier">
                                <label for="" class="form-label">Nama Barang</label>
                                <select name="barang" id="barangNama" required onchange="namaBarang()"
                                    class="form-control">
                                    <option value=""></option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"> {{ $product->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-labl">Satuan</label>
                                <input type="text" class="form-control" required readonly name="satuan" id="satuan">
                            </div>
                            {{-- <div class="mb-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="inputSelling" value="selling" type="radio"
                                        name="unit_selected" onclick="click_unit_selected(this)" id="radioDefault1" checked>
                                    <label class="form-check-label" for="radioDefault1">
                                        <span id="labelSelling"></span>
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="inputBuying" type="radio" value="buying"
                                        name="unit_selected" onclick="click_unit_selected(this)" id="radioDefault2">
                                    <label class="form-check-label" for="radioDefault2">
                                        <span id="labelBuying"></span>
                                    </label>
                                </div>
                            </div> --}}
                            <div class="row">
                                <div class="col-8">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Jumlah Barang </label>
                                        <input type="number" name="unit" required id="unit" class="form-control"
                                            oninput="hitungpcs()">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Jumlah pcs </label>
                                        <input type="text" name="countpcs" id="countpcs" readonly class="form-control">
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary w-100 btn-save">Tambah</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@section('script')
    <script src="{{ asset('select2/dist/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            $('#barangNama').select2({
                placeholder: "Pilih Barang"
            });

            // $('#container-supplier').css('display','none')

            $('#formProductIn').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('product.in.store') }}",
                    type: 'post',
                    data: formData,
                    beforeSend: function() {
                        $('#load').show();
                        $('.btn-save').attr('disabled', true)
                    },
                    success: function(res) {
                        // Notiflix.Notify.success(res.desc);

                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: res.desc,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('input.form-control').val('');
                        $('#labelSelling').text('')
                        $('#labelBuying').text('')
                        $('#barangNama').val(null).trigger('change');
                        $('#supplier-select').val(null).trigger('change');
                    },
                    error: function(err) {
                        console.log(err)
                        // Notiflix.Notify.failure(err.responseJSON.message)
                        Swal.fire({
                            position: "top-end",
                            icon: "warning",
                            title: err.responseJSON.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    },
                    complete: function() {
                        $('#load').hide();
                        $('.btn-save').attr('disabled', false)

                    }
                })
            })

        })

        function addSupplier() {
            $('#container-supplier').html(`
               <label for="" class="form-label">Supplier</label>
                <select  class="form-control" id="supplier-select" name="supplier">
                    <option value=""></option>
                    @foreach ($suppliers as $item)
                        <option value="{{ $item->id }}"> {{ $item->name }} </option>
                    @endforeach
                </select>
            `);
                   $('#supplier-select').select2({
                placeholder: "Pilih Supplier"
            });
            // $('#container-supplier').show();
            $('#supplier-select').attr('disabled', false)
        }

        function removeSupplier() {

            $('#container-supplier').html('');
            // $('#container-supplier').hide();
            $('#supplier-select').attr('disabled', true)
        }

        function hitungpcs() {

                let content_per_unit = $('#content_per_unit').val();
                let total_product = $('#unit').val();
                let total = total_product * content_per_unit;

                $('#countpcs').val(total)
        }

        function click_unit_selected(obj) {
            let val = $(obj).val();
            let content_per_unit = $('#content_per_unit').val();
            let total_product = $('#unit').val();
            let total = 0;
            if (val == 'buying') {
                if (total_product == null || total_product == 0) {
                    return false;
                } else {
                    total = content_per_unit * total_product;
                    $('#countpcs').val(total)
                }
            } else {
                if (total_product == null || total_product == 0) {
                    return false;
                } else {
                    $('#countpcs').val(total_product)
                }
            }
        }

        function namaBarang() {
            let id_barang = $('#barangNama option:selected').val();
            if (id_barang == '') {
                return false;
            }
            $.ajax({
                url: "{{ route('product.in.getData') }}",
                type: "GET",
                data: {
                    id_barang: id_barang
                },
                beforeSend: function() {
                    $('#load').show()
                },
                success: function(res) {
                    let data = res.data;
                    $('#satuan').val(data.short + ' - ' + data.unit_name )
                    // $('#inputSelling').attr('value', data.unit_selling);
                    // $('#labelSelling').text(data.unit_selling)
                    // $('#labelBuying').text(data.unit_buying)
                    $('#content_per_unit').val(data.content_per_unit)

                },
                error: function(err) {
                    Notiflix.Notify.failure('Terjadi Kesalahan')
                },
                complete: function() {
                    $('#load').hide();

                }
            })
        }
    </script>
@endsection
@endsection
