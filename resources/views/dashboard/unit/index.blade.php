@extends('dashboard.layouts.main')

@section('container')
    <h3>Unit</h3>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item active">Unit</li>
        </ol>
    </nav>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Unit</h5>
                        <a href="" data-bs-toggle="modal" data-bs-target="#tambahUnit"
                            class="btn btn-primary mb-2">Tambah Data</a>
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
                                    <th scope="col">Singkat</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($units as $item)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->short }}</td>
                                        <td><a href="" data-bs-toggle="modal"
                                                data-bs-target="#editUnit{{ $item->slug }}"
                                                class="btn btn-warning">Ubah</a>
                                            || <a data-bs-toggle="modal" data-bs-target="#deleteUnit{{ $item->slug }}"
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
    <div class="modal fade" id="tambahUnit" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Unit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/dashboard/unit-add" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Unit</label>
                            <input type="text" name="name" id="name" class="form-control" autofocus
                                autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="short" class="form-label">Singkatan</label>
                            <input type="text" name="short" id="short" class="form-control" autofocus
                                autocomplete="off">
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- modal Edit Category --}}
    @foreach ($units as $item)
        <div class="modal fade" id="editUnit{{ $item->slug }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Unit: {{ $item->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="/dashboard/unit-edit/{{ $item->slug }}" method="post">
                            @csrf
                            @method('put')
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Unit </label>
                                <input type="text" name="name" id="name" value="{{ $item->name }}"
                                    class="form-control" autofocus autocomplete="off">
                            </div>
                            <div class="mb-3">
                                <label for="short" class="form-label">Singkatan</label>
                                <input type="text" name="short" id="short" value="{{ $item->short }}"
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
    @foreach ($units as $item)
        <div class="modal" id="deleteUnit{{ $item->slug }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Anda yakin ingin menghapus {{ $item->name }} ?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <a href="/dashboard/unit-delete/{{ $item->slug }}" class="btn btn-danger">Hapus</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Vertically centered Modal-->
    @endforeach
@endsection
