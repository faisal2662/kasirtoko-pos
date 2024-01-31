@extends('dashboard.layouts.main')

@section('container')
    @include('sweetalert::alert')
    <h1>User</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index">Home</a></li>
            <li class="breadcrumb-item active">User</li>
        </ol>
    </nav>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Data User</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahUser">
                            Tambah Data
                        </button>
                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Alamat</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $item)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->username }}</td>
                                        <td>{{ $item->name_role }}</td>
                                        <td>{{ $item->address }}</td>
                                        <td><button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#ubahUser{{ $item->username }}" data-bs-toggle="tooltip"
                                                data-bs-placement="top" data-bs-title="Ubah User"><i
                                                    class="bi bi-pencil-square"></i>
                                            </button>
                                            ||
                                            <a href="/dashboard/user-delete/{{ $item->username }}"
                                                data-confirm-delete="true" class="btn btn-danger" data-bs-toggle="tooltip"
                                                data-bs-placement="top" data-bs-title="Hapus User"><i
                                                    class="bi bi-trash3"></i></a>
                                            || <button data-bs-toggle="modal"
                                                data-bs-target="#ubahPass{{ $item->username }}" data-bs-toggle="tooltip"
                                                data-bs-placement="top" data-bs-title="Ubah Password" class="btn btn-info"
                                                id="resetPass"><i class="bi bi-arrow-repeat"></i></button>
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

    <!-- Modal Tambah -->
    <div class="modal fade" id="tambahUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    {{-- <form action="/dashboard/user-add" class="needs-validation" method="post" novalidate> --}}
                    <form action="/dashboard/user-add" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control" required>

                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" id="username" required>

                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="role_id" id="status" class="form-select">
                                <option value="" disabled selected> -- Pilih Status-- </option>
                                <option value="adm001">Admin</option>
                                <option value="ksr001">kasir</option>
                            </select>
                        </div>
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group mb-3">
                            <input type="password" name="password" id="password" class="form-control" required>
                            <span class="input-group-text bg-success text-white" id="hide"style="cursor:pointer;"><i
                                    class="bi bi-eye-slash"></i></span>
                        </div>
                        <label for="confirmPass" class="form-label">Konfirmasi Password</label>
                        <div class="input-group mb-3">
                            <input type="password" id="confirmPass" class="input-group form-control">
                            <span class="input-group-text text-white" id="cek"></span>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea name="address" id="" rows="2" class="form-control"></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Ubah USer-->
    @foreach ($users as $item)
        <div class="modal fade" id="ubahUser{{ $item->username }}" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog  modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ubah User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        {{-- <form action="/dashboard/user-add" class="needs-validation" method="post" novalidate> --}}
                        <form action="/dashboard/user-edit/{{ $item->username }}" method="post">
                            @csrf
                            @method('put')
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" id="name" value="{{ $item->name }}"
                                    class="form-control" required>

                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" id="username" required
                                    value="{{ $item->username }}">

                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="role_id" id="status" class="form-select">
                                    <option value="" disabled> -- Pilih Status-- </option>
                                    <option @if ($item->role_id == 'adm001') selected @endif value="adm001">Admin</option>
                                    <option @if ($item->role_id == 'ksr001') selected @endif value="ksr001">kasir</option>
                                    {{-- <option value="owner">Owner</option> --}}
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea name="address" id="" rows="2" class="form-control">{{ $item->address }}</textarea>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Modal Ubah Password-->
    @foreach ($users as $item)
        <div class="modal fade" id="ubahPass{{ $item->username }}" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog  modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ubah User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="/dashboard/user-pass/{{ $item->username }}" method="post">
                            @csrf
                            @method('put')

                            <label for="password" class="form-label">Password</label>
                            <div class="input-group mb-3">
                                <input type="password" name="password" id="ubahPassword" class="form-control" required>
                                <span class="input-group-text bg-success text-white"
                                    id="ubahHide"style="cursor:pointer;"><i class="bi bi-eye-slash"></i></span>
                            </div>
                            <label for="confirmPass" class="form-label">Konfirmasi Password</label>
                            <div class="input-group mb-3">
                                <input type="password" id="ubahConfirmPass" class="input-group form-control">
                                <span class="input-group-text text-white" id="ubahCek"></span>
                            </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
    <script>
        const password = document.getElementById('password');
        const confirm = document.getElementById('confirmPass');
        const hide = document.getElementById('hide');
        const cek = document.getElementById('cek');

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
            // console.log(confirm.value)
            if (confirm.value == password.value) {
                cek.classList.remove('bg-danger')
                cek.innerHTML = '<i class="bi bi-check2"></i>'
                cek.classList.add('bg-success')
            } else {
                cek.classList.remove('bg-success')
                cek.innerHTML = '<i class="bi bi-exclamation-circle"></i>'
                cek.classList.add('bg-danger')
            }
        })
    </script>
    <script>
        const ubahPassword = document.getElementById('ubahPassword');
        const ubahConfirm = document.getElementById('ubahConfirmPass');
        const ubahHide = document.getElementById('ubahHide');
        const ubahCek = document.getElementById('ubahCek');

        ubahHide.addEventListener('click', () => {
            // console.log(hide)
            if (ubahHide.innerHTML == '<i class="bi bi-eye-slash"></i>') {
                ubahHide.innerHTML = '<i class="bi bi-eye"></i>';
                ubahPassword.setAttribute('type', 'text');
            } else {
                ubahPassword.setAttribute('type', 'password');
                ubahCek.innerHTML = '<i class="bi bi-eye-slash"></i>';
            }
        });

        ubahConfirm.addEventListener('keyup', () => {
            // console.log(confirm.value)
            if (ubahConfirm.value == ubahPassword.value) {
                ubahCek.classList.remove('bg-danger')
                ubahCek.innerHTML = '<i class="bi bi-check2"></i>'
                ubahCek.classList.add('bg-success')
            } else {
                ubahCek.classList.remove('bg-success')
                ubahCek.innerHTML = '<i class="bi bi-exclamation-circle"></i>'
                ubahCek.classList.add('bg-danger')
            }
        })
    </script>
@endsection
