<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Dashboard - NiceAdmin Bootstrap Template</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('img/favicon.png') }}" rel="icon">
    <link href="{{ asset('img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <!-- Vendor CSS Files -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/simple-datatables/style.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Sep 18 2023 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

    <!-- ======= Header ======= -->

    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="/dashboard" class="logo d-flex align-items-center">
                <span class="d-none d-lg-block">Toko Jaya Makmur</span>
            </a>
        </div><!-- End Logo -->


        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">


                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                        data-bs-toggle="dropdown">
                        <img src="{{ asset('img/person.svg') }}" alt="Profile" class="rounded-circle">
                        <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->name }}</span>
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="users-profile">
                                <i class="bi bi-person"></i>
                                <span>Profil Saya</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="/logout">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign Out</span>
                            </a>
                        </li>

                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->

            </ul>
        </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->

    <!-- ======= Sidebar ======= -->
    @include('sweetalert::alert')
    <div class="container " style="margin-top:90px;width:40rem;">
        <section>
            <div class="card">
                <div class="card-body">
                    <div class="card-title">DATA PROFILE</div>
                    <div class="text-center">
                        <img src="{{ asset('img/person.svg') }}" width="150px" alt="...">
                    </div>
                    <div class="row bg-info mx-3 mt-2 bg-opacity-10 border border-info border-start-0 rounded-end">
                        <div class="col-3 pt-3 mb-3">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Nama</li>
                                <li class="list-group-item">Username</li>
                                <li class="list-group-item">Alamat</li>
                                <li class="list-group-item pb-4 pt-3">Password</li>
                            </ul>
                        </div>
                        <div class="col-6 pt-3">
                            @foreach ($users as $item)
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">{{ $item->name }} </li>
                                    <li class="list-group-item">{{ $item->username }}</li>
                                    <li class="list-group-item">{{ $item->address }}</li>
                                    <li class="list-group-item"><button style="float: left; margin-right:10px;"
                                            class="btn btn-info" data-bs-toggle="modal"
                                            data-bs-target="#ubahPassword"><i class="bi bi-arrow-repeat"></i>
                                        </button>
                                        <p class="text-muted pt-2 ">Ubah Password</p>

                                    </li>
                                </ul>
                            @endforeach
                        </div>
                    </div>
                    <button data-bs-toggle="modal" data-bs-target="#ubahData" class="btn btn-success "
                        style="margin-left:30px; margin-top: 18px">Ubah
                        Data</button>
                    <a href="/dashboard" class="btn btn-secondary mt-3 " style="float: right;">Kembali</a>
                </div>
            </div>
        </section>
    </div>
    {{-- </main><!-- End #main --> --}}
    <!-- Modal  Ubah Password-->
    <div class="modal fade" id="ubahPassword" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Ubah Password <div
                            class="spinner-border text-info mx-3" style="display:none;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 password-lama">
                        <div id="alert"></div>
                        <label for="passwordLama" class="form-label">Kata Sandi Lama</label>
                        <input type="password" name="passwordLama" id="passwordLama" class="form-control">
                        <button type="submit" class="btn btn-success mt-2" id="cek">kirim</button>
                    </div>
                    <form action="/dashboard/users-profile/newPass" method="post">
                        @csrf
                        <div id="password-baru" style="display: none;">
                            <label for="passwordBaru" class="form-label">Masukkan Password Baru</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control">
                                <span class="input-group-text bg-success text-white"
                                    id="hide"style="cursor:pointer;"><i class="bi bi-eye-slash"></i></span>
                            </div>
                            <p id="min" class="text-white text-muted mb-2"></p>
                            <label for="passwordConfirm" class="form-label ">Konfirmasi Password</label>
                            <div class="mb-3 input-group">
                                <input type="password" id="passwordConfirm" class="form-control input-group">
                                <span class="input-group-text text-white" id="cekConfirm"></span>
                            </div>
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ubahData" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Ubah data Profil <div
                            class="spinner-border text-info mx-3" style="display:none;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    @foreach ($users as $item)
                        <form action="/dashboard/users-profile/update/{{ $item->username }}" method="post">
                            @csrf
                            <label for="name" class="form-label">Masukkan Nama</label>
                            <div class="input-group">
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ $item->name }}">
                            </div>
                            <label for="username" class="form-label ">Username</label>
                            <div class="mb-3 input-group">
                                <input type="text" id="username" name="username"
                                    class="form-control input-group" value="{{ $item->username }}">
                            </div>
                            <label for="alamat" class="form-label">Alamat</label>
                            <div class="mb-3 input-group">
                                <textarea name="address" id="address" class="form-control" cols="30" rows="3">{{ $item->address }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </form>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        const inputPassOld = document.getElementById('passwordLama');
        const bodyPassOld = document.getElementsByClassName('password-lama')[0];
        const inputPassNew = document.getElementById('password');
        const bodyPassNew = document.getElementById('password-baru');
        const check = document.getElementById('cek');
        const hide = document.getElementById('hide');
        const cekNew = document.getElementById('cekConfirm');
        const confirm = document.getElementById('passwordConfirm');
        const load = document.getElementsByClassName('spinner-border')[0];

        // console.log(bodyPass.lastElementChild)

        check.addEventListener('click', () => {
            load.style.display = '';
            fetch('/dashboard/users-profile/confirm?password=' + inputPassOld.value)
                .then((res) => res.json())
                .then(function(data) {
                    if (data) {
                        bodyPassOld.style.display = 'none';
                        bodyPassNew.style.display = '';

                        hide.addEventListener('click', () => {
                            // console.log(hide)
                            if (hide.innerHTML == '<i class="bi bi-eye-slash"></i>') {
                                hide.innerHTML = '<i class="bi bi-eye"></i>';
                                password.setAttribute('type', 'text');
                            } else {
                                password.setAttribute('type', 'password');
                                hide.innerHTML = '<i class="bi bi-eye-slash"></i>';
                            }
                        });

                        confirm.addEventListener('keyup', () => {

                            if (confirm.value == password.value) {
                                cekNew.classList.remove('bg-danger')
                                cekNew.innerHTML = '<i class="bi bi-check2"></i>'
                                cekNew.classList.add('bg-success')
                            } else {
                                cekNew.classList.remove('bg-success')
                                cekNew.innerHTML = '<i class="bi bi-exclamation-circle"></i>'
                                cekNew.classList.add('bg-danger')
                            }

                        });
                        load.style.display = 'none';
                    } else {
                        console.log('tidak sama')
                        let isi =
                            ' <div class="alert alert-danger">Password Salah</div>';
                        document.getElementById('alert').innerHTML = isi;
                        setTimeout(() => {
                            document.getElementById('alert').innerHTML = '';
                        }, 3000);
                        load.style.display = 'none';
                    }
                });
        });
    </script>

    <!-- Vendor JS Files -->

    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>


</body>

</html>
