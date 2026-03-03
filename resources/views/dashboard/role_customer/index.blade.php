@extends('dashboard.layouts.main')
@section('title')
    Kasir | Role Customer
@endsection
@section('container')
    @include('sweetalert::alert')

    <h3>Unit</h3>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Role Customer</li>
        </ol>
    </nav>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <a href="" data-bs-toggle="modal" data-bs-target="#tambahRole"
                            class="btn btn-primary mb-2 mt-3 float-end">Tambah Data</a>
                        <h5 class="card-title">Role Customer </h5>
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($role_customer as $item)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $item->name }}</td>

                                        <td><a href="" data-bs-toggle="modal"
                                                data-bs-target="#editRole{{ $item->id }}"
                                                class="btn btn-warning">Ubah</a>
                                            || <a data-bs-toggle="modal" data-bs-target="#deleteRole{{ $item->id }}"
                                                class="btn btn-danger">Hapus</a></td>
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

    {{-- modal --}}
    <div class="modal fade" id="tambahRole" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('role_customer.store') }}" id="formRole" method="post">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Role</label>
                            <input type="text" name="name" id="name" class="form-control" autofocus
                                autocomplete="off">
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modal Edit Category --}}
    @foreach ($role_customer as $item)
        <div class="modal fade" id="editRole{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Role: {{ $item->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('role_customer.update', $item->id) }}" method="post">
                            @csrf
                            @method('put')
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Unit </label>
                                <input type="text" name="name" id="name" value="{{ $item->name }}"
                                    class="form-control" autofocus autocomplete="off">
                            </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-warning">Perbarui</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Vertically centered Modal-->
    @endforeach

    {{-- modal delete --}}
    @foreach ($role_customer as $item)
        <div class="modal" id="deleteRole{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Anda yakin ingin menghapus {{ $item->name }} ?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <a href="{{ route('role_customer.destroy', $item->id) }}}}" class="btn btn-danger">Hapus</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Vertically centered Modal-->
    @endforeach

    @section('script')
        <script>
            $('#formRoel')
        </script>
    @endsection
@endsection
