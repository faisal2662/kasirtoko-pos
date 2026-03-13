@extends('dashboard.layouts.main')
@section('title')
    Setting Print | Kasir
@endsection
@section('container')
    <h3>Unit</h3>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Setup Print</li>
        </ol>
    </nav>

    <section class="section profile">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body profile-overview">
                        <div class="float-end mt-4">
                            <div class="btn btn-danger" data-bs-target="#showPreview" data-bs-toggle="modal"> <i
                                    class="bi bi-eye"></i> Lihat </div>
                            {{-- <button class="btn btn-primary" onclick="printStruk()">Print</button> --}}
                            {{-- <button class="btn btn-primary" onclick="printerReceipt()">Print</button> --}}
                        </div>
                        <h5 class="card-title">Setup Print Unit</h5>
                        <form action="" id="formSetup" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id_store_setting" value="{{ $setup->id }}">
                            <div class="row mt-3">
                                <div class="col-lg-3 col-md-4 label ">Nama Printer</div>
                                <div class="col-lg-9 col-md-8">
                                   <input type="text" class="form-control" name="name_printer" value="{{ $setup->name_printer }}">
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-lg-3 col-md-4 label ">Nama Toko</div>
                                <div class="col-lg-9 col-md-8"> <input type="text" name="store_name" id=""
                                        value="{{ $setup->store_name ?? '' }}" class="form-control"> </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label ">Alamat</div>
                                <div class="col-lg-9 col-md-8">
                                    <textarea name="address" id="" cols="30" rows="3" class="form-control"> {{ $setup->address ?? '' }} </textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label ">Kota</div>
                                <div class="col-lg-9 col-md-8">
                                    <input type="text" class="form-control" name="city"
                                        value="{{ $setup->city ?? '' }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label ">Email</div>
                                <div class="col-lg-9 col-md-8"> <input type="email" name="email" id=""
                                        class="form-control" value="{{ $setup->email ?? '' }}"> </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label ">No. Hp</div>
                                <div class="col-lg-9 col-md-8"> <input type="number" name="phone" id=""
                                        class="form-control" value="{{ $setup->phone ?? '' }}"> </div>
                            </div>
                            <div class="row ">
                                <div class="col-lg-3 col-md-4 label ">Logo</div>
                                <div class="col-lg-9 col-md-8"> <input type="file" name="logo" id=""
                                        value="{{ basename($setup->logo) ?? '' }}" class="form-control"> </div>
                            </div>
                            @if ($setup->logo != '' || $setup->logo != null)
                                <div class="row ">
                                    <div class="col-lg-3 col-md-4 label ">Tampilan Logo</div>
                                    <div class="col-lg-9 col-md-8"><img id="image-logo" style="max-height: 250px;"
                                            src="{{ asset('storage/' . $setup->logo) }}" class="rounded mx-auto d-block"
                                            alt="..."></div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label "> Catatan Kaki </div>
                                <div class="col-lg-9 col-md-8">
                                    <textarea name="footer_note" id="" cols="30" rows="3" class="form-control"> {{ $setup->footer_note ?? '' }} </textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label "> Pesan Kaki </div>
                                <div class="col-lg-9 col-md-8">
                                    <textarea name="footer_message" id="" cols="30" rows="3" class="form-control"> {{ $setup->footer_message ?? '' }} </textarea>
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-lg-3 col-md-4 label ">Qris</div>
                                <div class="col-lg-9 col-md-8"> <input type="file" name="qris_image" id=""
                                        value="{{ basename($setup->qris_image) ?? '' }}" class="form-control"> </div>
                            </div>
                            @if ($setup->qris_image != '' || $setup->qris_image != null)
                                <div class="row ">
                                    <div class="col-lg-3 col-md-4 label ">Tampilan Qris</div>
                                    <div class="col-lg-9 col-md-8"><img id="image-qris" style="max-height: 250px;"
                                            src="{{ asset('storage/' . $setup->qris_image) }}"
                                            class="rounded mx-auto d-block" alt="..."></div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label "> Tampilkan Logo </div>
                                <div class="col-lg-9 col-md-8">

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" value="1" type="radio" name="show_logo"
                                            id="radioDefault1"
                                            {{ isset($setup->show_logo) && $setup->show_logo == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="radioDefault1">
                                            Ya
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" value="0" name="show_logo"
                                            {{ isset($setup->show_logo) && $setup->show_logo == 0 ? 'checked' : '' }}
                                            id="radioDefault2">
                                        <label class="form-check-label" for="radioDefault2">
                                            Tidak
                                        </label>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label "> Tampilkan Alamat </div>
                                <div class="col-lg-9 col-md-8">

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" value="1" type="radio"
                                            name="show_address" id="radioDefault1"
                                            {{ isset($setup->show_address) && $setup->show_address == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="radioDefault1">
                                            Ya
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" value="0"
                                            name="show_address"
                                            {{ isset($setup->show_address) && $setup->show_address == 0 ? 'checked' : '' }}
                                            id="radioDefault2">
                                        <label class="form-check-label" for="radioDefault2">
                                            Tidak
                                        </label>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label "> Tampilkan No. Hp </div>
                                <div class="col-lg-9 col-md-8">

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" value="1" type="radio" name="show_phone"
                                            id="radioDefault1"
                                            {{ isset($setup->show_phone) && $setup->show_phone == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="radioDefault1">
                                            Ya
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" value="0" name="show_phone"
                                            {{ isset($setup->show_phone) && $setup->show_phone == 0 ? 'checked' : '' }}
                                            id="radioDefault2">
                                        <label class="form-check-label" for="radioDefault2">
                                            Tidak
                                        </label>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label "> Tampilkan Email </div>
                                <div class="col-lg-9 col-md-8">

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" value="1" type="radio" name="show_email"
                                            id="radioDefault1"
                                            {{ isset($setup->show_email) && $setup->show_email == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="radioDefault1">
                                            Ya
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" value="0" name="show_email"
                                            {{ isset($setup->show_email) && $setup->show_email == 0 ? 'checked' : '' }}
                                            id="radioDefault2">
                                        <label class="form-check-label" for="radioDefault2">
                                            Tidak
                                        </label>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label "> Tampilkan Qris </div>
                                <div class="col-lg-9 col-md-8">

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" value="1" type="radio" name="show_qris"
                                            id="radioDefault1"
                                            {{ isset($setup->show_qris) && $setup->show_qris == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="radioDefault1">
                                            Ya
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" value="0" name="show_qris"
                                            {{ isset($setup->show_qris) && $setup->show_qris == 0 ? 'checked' : '' }}
                                            id="radioDefault2">
                                        <label class="form-check-label" for="radioDefault2">
                                            Tidak
                                        </label>

                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-8">
                                    <button type="submit" class="btn btn-primary btn-update w-100">Update</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <div class="modal" id="showPreview" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered ">
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
                            <image height="100px" style="text-align:center;"
                                src="{{ asset('storage/' . $setup->logo) }}">
                        </p>
                    @endif
                    <p class="text-center" >

                        ===================================================
                    </p>
                    <p>

                        Tanggal <span style="margin-left:10px;">: {{ now() }} </span>
                    </p>
                    <p>

                        Kasir <span style="margin-left:10px;">: {{ Auth::user()->name }} </span>
                    </p>
                    <p>

                        No. Inv <span style="margin-left:10px;">: INV/XXXXXXX</span>
                    </p>
                    <p class="text-center" >

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
                        <tbody>
                            <tr>
                                <td colspan="4" class="text-center" >--------------------------------------------------------------------------</td>
                            </tr>
                            <tr>
                                <td>Indomie Goreng</td>
                                <td>2.500</td>
                                <td>3</td>
                                <td>7.500</td>
                            </tr>
                            <tr>
                                <td>Indomie Goreng</td>
                                <td>2.500</td>
                                <td>3</td>
                                <td>7.500</td>
                            </tr>
                            <tr>
                                <td>Indomie Goreng</td>
                                <td>2.500</td>
                                <td>3</td>
                                <td>7.500</td>
                            </tr>

                            <tr>
                                <td colspan="4" class="text-center">----------------------------------------------------------------------------</td>
                            </tr>

                        </tbody>
                    </table>
                    <p>Total <span style="margin-left:350px">22.000</span> </p>
                    <p>Bayar <span style="margin-left:350px">25.000</span> </p>
                    <p>Bayar <span style="margin-left:350px">3.000</span> </p>
                    <p class="text-center" >--------------------------------------------------------------------------------</p>
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
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>

                </div>
            </div>
        </div>
    </div>


@section('script')
    <script>
        $('#formSetup').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('store_setting.store') }}",
                type: 'post',
                data: formData,
                cache: false,
                contentType: false, // WAJIB: Agar jQuery tidak mengatur Content-Type
                processData: false, // WAJIB: Agar jQuery tidak mengubah FormData menjadi string
                beforeSend: function() {
                    $('.btn-update').attr('disabled', true);
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
                        title: 'Berhasil !',
                        text: res.desc,
                        time: 2000
                    });
                    $('#image-logo').attr('src', "{{ asset('storage/:url') }}".replace(':url', res
                        .path_logo))
                    $('#image-qris').attr('src', "{{ asset('storage/:url') }}".replace(':url', res
                        .path_qris))
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
                    $('.btn-update').attr('disabled', false);

                }
            })
        })
    </script>
@endsection
@endsection
