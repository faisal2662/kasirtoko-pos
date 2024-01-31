@extends('dashboard.layouts.main')
@section('container')
    @include('sweetalert::alert')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <h1>Penjualan</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index">Home</a></li>
            <li class="breadcrumb-item active">Penjualan</li>
        </ol>
    </nav>

    <div class="row">

        <div class="col-5">
            <div class="card">
                <div class="card-body">

                    <h5 class="card-title"> Penjualan </h5>
                    <form class="row g-3">
                        {{-- <div class="col-md-12">
                            <label for="name" class="form-label">Nama Pembeli</label>
                            <input type="text" name="name" class="form-control" id="name">
                        </div> --}}
                        <div class="col-md-12">
                            <label for="nambar" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" autofocus id="nambar" onkeyup="fetchData()"
                                name="nambar">
                            <ul class="list-group" id="tbodyfordata" style="position:fixed;width:270px;z-index:1; ">
                                {{-- <li class="list-group-item" onclick="tes()">lkfhjsd</li>
                                <li class="list-group-item">lkfhjsd</li> --}}
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <label for="nambar" class="form-label">Kode Barang</label>
                            <input type="text" disabled name="name" class="form-control" id="kodbar">
                        </div>
                        <div class="col-6">
                            <label for="harsat" class="form-label">Harga Satuan</label>
                            <input type="text" class="form-control" id="harsat" disabled>
                        </div>
                        <div class="col-6">
                            <label for="jumbel" class="form-label">Jumlah Beli</label>
                            <input type="number" name="quantity" class="form-control" id="jumbel">
                        </div>
                        <div class="col-md-12">
                            <label for="jumlah" class="form-label">Jumlah</label>
                            <input type="text" class="form-control" name="jumlah" id="jumlah" disabled>
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-primary" id="tambah" name="tambah">Tambah</button>
                            <button type="reset" class="btn btn-secondary" onclick="reset()">Reset</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <div class="col-7">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center fs-4"> TOKO SUMBER MAKMUR </h5>
                    <p class="text-center" style="margin-top:-20px; font-size:13px;">Jl. Raya Mauk KM.12 Kecamatan Sepatan
                        <hr>
                    </p>
                    <form class="row g-3" action="/dashboard/sale" method="post" id="form-keranjang">
                        @csrf
                        <div class="row">
                            <div class="col-4">
                                <div class="mb-3">
                                    <select class="js-example-basic-single" name="state">
                                        <option value="AL">Alabama</option>
                                        ...
                                        <option value="WY">Wyoming</option>
                                    </select>
                                    {{-- <label class="form-label">Nama :</label>
                                    <label for="nama" id="nama" class="form-label">Nama</label>
                                    <input type="text" class="form-control" required name="name"> --}}
                                </div>
                            </div>
                            <div class="col-5 mt-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="pelanggan">
                                    <label class="form-check-label" for="pelanggan">Pelanggan</label>
                                </div>
                            </div>
                        </div>

                        <table class="table table-striped-columns" style="font-size:15px;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah Barang</th>
                                    <th>Harga Total</th>
                                </tr>
                            </thead>
                            <tbody id="keranjang">

                            </tbody>
                            <tbody>
                                <tr>
                                    <td colspan="3">Sub Total:</td>
                                    <td id="subTotal"></td>
                                </tr>
                                <tr>
                                    <td colspan="3">Diskon:</td>
                                    <td id="DiskonTotal"></td>
                                    <input type="text" hidden name="diskon" id="diskon">
                                </tr>
                                <tr>
                                    <td colspan="3">Jumlah Total:</td>
                                    <td id="jumlahTotal"></td>
                                    <input type="text" name="jumtot" hidden id="jumtotInput">
                                </tr>
                            </tbody>
                        </table>
                        <div class="mt-1">
                            <div class="row">
                                <div class="col-5">
                                    <label for="bayar" class="form-label">Bayar</label>
                                    <input type="text" class="form-control" id="bayar">
                                </div>
                                <div class="col-5">
                                    <label for="kembalian" class="form-label">Kembalian</label>
                                    <input type="text" class="form-control" disabled id="kembalian">
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" name="simpan">Simpan</button>
                            <input type="submit" value="Cetak" name="cetak" class="btn btn-secondary">
                            {{-- <button class="btn btn-secondary" type="submit" name="cetak">Cetak</button> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
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
            const url = "/search" + "?search=" + val;

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
    </script>
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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection
