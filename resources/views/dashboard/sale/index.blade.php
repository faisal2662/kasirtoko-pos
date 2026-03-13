@extends('dashboard.layouts.main')
@section('container')
@section('title')
    Kasir
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

        <div class="col-12 col-md-4" >
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
                    <p class="text-center" style="margin-top:-20px; font-size:13px;"> {{ $setup->address . ' - ' . $setup->city }}
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
                                <div class="float-end mt-3"><a href="{{ route('invoice.index') }}" target="_blank" class="btn btn-warning"> <i class="bx bx-history"></i> Riwayat Transaksi </a></div>
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
                                    <td  class="text-center"> <input type="text" name="sub_total" id="subTotal" style="text-align: center;" class="form-control rupiah" readonly="readonly">  </td>
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
                                    <input type="text" name="bayar_customer" class="form-control rupiah" required id="bayar">
                                </div>
                                <div class="col-5">
                                    <label for="kembalian" class="form-label">Kembalian</label>
                                    <input type="text" name="kembalian" readonly class="form-control"  id="kembalian">
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" name="simpan" ><i class="bi bi-floppy"></i> &nbsp; Simpan</button>
                            {{-- <input type="submit" value="Cetak" name="cetak" class="btn btn-secondary"> --}}
                            <button class="btn btn-secondary" type="submit" value="cetak" name="cetak"><i class="bi bi-printer"></i> &nbsp; Simpan & Cetak</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @section('script')
        <script src="{{ asset('js/jquery.number.min.js') }}"></script>
        <script src="{{ asset('select2/dist/js/select2.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('.rupiah').number(true, 0)
                $('body').addClass('toggle-sidebar')
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
                        $('#kembalian').val($.number(sisa,0,','));
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
                                pilihData(d.name, d.id, d.harga, d.harga_beli, d.satuanKecil, d.satuanBesar, d.satuanKecilName, d.satuanBesarName);

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
                    getCustomer();

                }, 60000);
            });

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
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan: ' + err.responseJSON.message
                        });
                    }
                })
            }

            function hapusBaris(obj){
                $(obj).closest('tr').remove()
            }

            // Toggle Checkbox
            $('.form-check-input').on('click', function() {
                console.log($(this).is(':checked'));
            });
        </script>

        {{-- <script>
        const se = document.getElementById('nambar');
        const harsat = document.getElementById('harsat');
        const kodbar = document.getElementById('kodbar');
        const jumlah = document.getElementById('jumlah');
        const jumbel = document.getElementById('jumbel');
        const keranjang = document.getElementById('keranjang');
        const tambah = document.getElementById('tambah');
        // const remove = document.getElementById('remove');
        const remove = document.querySelectorAll('#remove');
        const keranjangInput = document.getElementById('keranjang-input');
        // const jumlahTotal = document.getElementById('jumlahTotal')


        function fetchData() {
            const val = document.getElementById('nambar').value;
            const harsat = document.getElementById('harsat');
            const kodbar = document.getElementById('kodbar');
            const jumlah = document.getElementById('jumlah');
            const jumbel = document.getElementById('jumbel');
            const keranjang = document.getElementById('keranjang');
            const tambah = document.getElementById('tambah');
            const jumlahTotal = document.getElementById('jumlahTotal')
            //Search Value
            //Search Url
            const url = "{{ route('search.product') }}?search=" + val;

            console.log(url);
            fetch(url)
                .then((resp) => resp.json()) //Transform the data into json
                .then(function(data) {
                    // console.log(data);

                    var tbodyref = document.getElementById('tbodyfordata');
                    tbodyref.style.display = 'block'
                    // console.log(tbodyref);
                    tbodyref.innerHTML = '';

                    let categories = data;
                    // console.log(categories);
                    categories.map(function(category) {
                        let li = document.createElement('li');
                        li.innerText = category.name_product;
                        tbodyref.appendChild(li)
                        li.classList.add('list-group-item', 'hasil')

                    });

                    const se = document.getElementById('nambar');

                    const hasil = document.querySelectorAll('.hasil');
                    // console.log(hasil);
                    hasil.forEach(hsl => {
                        hsl.addEventListener('click', function() {
                            // console.log(hsl.textContent)
                            se.value = hsl.textContent
                            tbodyref.style.display = 'none'
                            const urlResult = "/result" + "?result=" + hsl.textContent
                            console.log(urlResult)
                            fetch(urlResult)
                                .then((res) => res.json())
                                .then(function(data) {
                                    console.log(data)
                                    harsat.value = rupiah(data.unit_price)
                                    kodbar.value = data.slug
                                    jumbel.addEventListener('keyup', (e) => {
                                        let j = data.unit_price * jumbel.value
                                        jumlah.value = rupiah(j)
                                    })

                                })
                                .catch(function(error) {
                                    console.log(error);
                                })
                        })
                    })


                })
                .catch(function(error) {
                    console.log(error);
                })

            function createNode(element) {
                return document.createElement(element);
            }
            const rupiah = (number) => {
                return new Intl.NumberFormat("id-ID", {
                    style: "currency",
                    currency: "IDR"
                }).format(number);
            }
        }
        const diskon = document.getElementById('DiskonTotal');
        const diskonInput = document.getElementById('diskon');
        let i = 0;
        const jumTotInput = document.getElementById('jumtotInput');
        const subTot = document.getElementById('subTotal');
        const focus = document.getElementById('nambar');
        tambah.addEventListener('click', function() {
            ++i;
            // console.log('tambah');
            let number1 = 0;
            let m = keranjang.lastElementChild
            let kolNo = document.createElement('td');
            let kolNama = document.createElement('td');
            let kolJum = document.createElement('td');
            let kolTot = document.createElement('td');
            let inputSpan = document.createElement('span');
            let kerInput =
                ' <input type="text" hidden name = "inputs[' + i +
                '][product_slug]" value="' + kodbar.value +
                '" id="product_slug"><br>  <input hidden type="text" name="inputs[' + i +
                '][jumbel]" value="' + jumbel.value +
                '" id="jumbel" > <br> <input hidden type="text" name="inputs[' + i +
                '][jumlah]" value="' + convers(jumlah.value) + '" id="jumlah"></td>';
            let baris = document.createElement('tr');
            let btn = document.createElement('buttton')

            btn.innerHTML = '<i class="bi bi-x-circle"></i>';
            btn.classList.add('badge', 'bg-danger', 'text-white', 'text-center')
            btn.setAttribute('id', 'remove')
            // btn.setAttribute('onclick', 'hapus()')
            kolNo.appendChild(btn)
            kolNama.innerHTML = se.value
            kolJum.innerHTML = jumbel.value
            kolTot.innerHTML = jumlah.value
            kolTot.classList.add('hartotal')
            baris.appendChild(kolNo)
            baris.appendChild(kolNama)
            baris.appendChild(kolJum)
            baris.appendChild(kolTot)
            baris.setAttribute('id', 'baris')
            baris.setAttribute('onclick', 'hapus(this)')
            inputSpan.innerHTML = kerInput
            inputSpan.style.display = 'none';
            baris.appendChild(inputSpan);
            keranjang.appendChild(baris)
            // keranjangInput.innerHTML = kerInput
            // keranjangInput.style.display = 'none';

            // console.log(m)
            let s = 0;
            let t = 0;
            if (m != null) {
                const hargaTotal = document.querySelectorAll('.hartotal')
                let sum = 0;
                hargaTotal.forEach(harga => {
                    let har = harga.innerText
                    let h = Number(har.replace(/[^0-9.-]+/g, ""))
                    sum += h
                })
                t = sum;
                if (sum >= 100) {
                    diskon.innerHTML = '10%';
                    diskonInput.value = 0.1;
                    t = sum - (sum * 0.1);
                } else {
                    diskon.innerHTML = '';
                }
                subTot.innerHTML = rupiah(sum)
                jumlahTotal.innerHTML = rupiah(t)
                jumTotInput.value = t + "000";
            } else {
                let hargaT = jumlah.value
                let number2 = Number(hargaT.replace(/[^0-9.-]+/g, ""));
                t = number2;
                if (number2 >= 100) {
                    diskon.innerHTML = '10%';
                    diskonInput.value = 0.1;
                    t = number2 - (number2 * 0.1)
                }
                subTot.innerHTML = rupiah(number2)
                jumlahTotal.innerHTML = rupiah(t)
                jumTotInput.value = t + `000`;
            }

            reset();
            focus.focus();
        });

        function convers(r) {
            $num = Number(r.replace(/[^0-9.-]+/g, ""));
            return $num + "000"
        }
        const rupiah = (number) => {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR"
            }).format(number + "000");
        }
        const Rupiah = (number) => {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR"
            }).format(number);
        }

        function hapus(e) {
            let s = 0;
            let t = 0;
            console.log('remove')
            e.parentNode.removeChild(e)
            const hargaTotal = document.querySelectorAll('.hartotal')
            let sum = 0;
            hargaTotal.forEach(harga => {
                let har = harga.innerText
                let h = Number(har.replace(/[^0-9.-]+/g, ""))
                sum += h
            })
            // s = sum + "000";
            t = sum;
            if (sum >= 100) {
                diskon.innerHTML = '10%';
                diskonInput.value = 0.1;

                t = sum - (sum * 0.1);
            } else {
                diskon.innerHTML = '';
            }
            subTot.innerHTML = rupiah(sum)
            jumlahTotal.innerHTML = rupiah(t)
            jumTotInput.value = t

        }

        function reset() {
            se.value = '';
            harsat.value = '';
            kodbar.value = '';
            jumlah.value = '';
            jumbel.value = '';
        }
        const bayar = document.getElementById('bayar');
        const kembalian = document.getElementById('kembalian');

        bayar.addEventListener('keyup', function(e) {
            bayar.value = formatRupiah(this.value, 'Rp ')
            let kembali = convers(bayar.value) - convers(jumlahTotal.innerText)
            kembalian.value = Rupiah(kembali)
        })

        function formatRupiah(angka, prefix) {
            let number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(","),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
        };
    </script>
    <script>
        const toggle = document.getElementsByClassName('form-check-input')[0];

        toggle.addEventListener('click', (e) => {
            console.log(e.target.checked)

        })
    </script> --}}
        {{-- <script>
        const cetak = document.getElementById('cetak');
        const formKeranjang = document.getElementById('form-keranjang');

        cetak.addEventListener('click', function() {
            event.preventDefault();

            // const formData = {};
            // new formData(formKeranjang).forEach((value, key) => {
            //     formData[key] = value;
            // })
            let name = formKeranjang.name.value
            let quantity = formKeranjang.jumbel
            let token = formKeranjang._token.value
            console.log(token)
            fetch('/dashboard/sale/print', {
                    method: 'POST',
                    headers: {
                        'accept': 'application/json',
                        'Content-Type': 'application/json; charset=UTF-8',
                    },
                    body: JSON.stringify({
                        _token: token,
                        name: 'faisal',
                        jumbel: 4
                    }),
                    keepalive: true
                })
                .then((response) => response.json())
                .then((json) => console.log(json));
            quantity.forEach(e => {
                console.log(e.value)
            });
        })
    </script> --}}
    @endsection
@endsection
