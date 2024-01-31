@extends('dashboard.layouts.main')

@section('container')
    @include('sweetalert::alert')
    <h3>Barang Keluar</h3>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item active">Barang Keluar</li>
        </ol>
    </nav>



    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title ">Data Barang <div style="margin-left:10px; display:none;"
                                class="spinner-border text-primary loading" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </h5>
                        {{-- <a href="/dashboard/product-add" class="btn btn-primary mb-2">Tambah Data</a>
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif --}}
                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Kode Transaksi</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Information</th>
                                    <th scope="col">Diskon</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productOuts as $item)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $item->code_product_out }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->date }}</td>
                                        <td>{{ $item->information }}</td>
                                        <td>{{ $item->disc * 100 }} %</td>
                                        <td>@currency($item->total)</td>
                                        <td>
                                            <button id="sale_id" onclick="details(this)" data-bs="{{ $item->sale_id }}"
                                                class="btn btn-secondary"><i class="bi bi-clock-history"></i></button>
                                            ||
                                            <a href="/dashboard/productOut-delete/{{ $item->sale_id }}"
                                                class="btn btn-danger" data-confirm-delete="true"><i
                                                    class="bi bi-trash"></i></a>

                                        </td>
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
    <div class="card" id="cardDetail"
        style="width: 26rem ; height: 25rem; position:fixed;margin: -140px 0 0 -150px; top:50%; left: 50%; display:none;">
        <div class="card-header header-history">
        </div>
        <div class="card-body" style="overflow-y: scroll;">
            <p style="float: right">Tanggal : <span id="tgl-trans"></span></p>
            <h5 class="card-title title-history"></h5>

            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Jumlah Beli </th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody id="list-pay">
                </tbody>
            </table>

        </div>
        <div class="card-footer text-end">
            <button class="btn btn-secondary back">Kembali</button>
        </div>
    </div>
    <script>
        const cardDetail = document.getElementById('cardDetail');
        const detail = document.getElementById('sale_id');
        const header = document.getElementsByClassName('header-history')[0];
        const list = document.getElementById('list-pay');
        const date = document.getElementById('tgl-trans');
        const back = document.getElementsByClassName('back')[0];
        const load = document.getElementsByClassName('loading')[0];

        function details(e) {
            let code = e.dataset.bs;
            load.style.display = 'inline-block';
            fetch('/dashboard/productOut/detail?code=' + code)
                .then((resp) => resp.json())
                .then(function(data) {
                    console.log(data)
                    header.innerHTML = data[0].name.toUpperCase();
                    date.innerHTML = data[0].date
                    document.getElementsByClassName('title-history')[0].innerHTML = "Riwayat : " + data[0].sale_id;
                    let histories = data;
                    list.innerHTML = ''
                    let isi = '';
                    histories.map(function(history) {
                        let tr = document.createElement('tr')
                        isi = '<td>' + history.name_product + '</td> <td>' + history.quantity +
                            '</td><td>' + rupiah(history.price) + '</td>';
                        // console.log(history.name)
                        tr.innerHTML = isi
                        list.appendChild(tr)

                    })
                    let tr = document.createElement('tr')
                    let total = ' <th colspan="2">Jumlah</th> <th id="total-pay">' + rupiah(data[0].total) + '</th>'
                    tr.innerHTML = total
                    list.append(tr)
                    load.style.display = 'none';
                    cardDetail.style.display = cardDetail.style.display === 'none' ? '' : 'none';
                })
        }

        back.addEventListener('click', () => {
            cardDetail.style.display = 'none';
        })
        const rupiah = (number) => {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR"
            }).format(number);
        }
    </script>

    {{-- modal delete --}}
    @foreach ($productOuts as $item)
        <div class="modal" id="deleteProductOut{{ $item->sale_id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Anda yakin ingin menghapus {{ $item->name }} ?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <a href="/dashboard/productOut-delete/{{ $item->sale_id }}" class="btn btn-danger">Hapus</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Vertically centered Modal-->
    @endforeach
@endsection
