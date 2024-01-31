@extends('dashboard.layouts.main')

@section('container')
    <h3>Category</h3>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item active">Category</li>
        </ol>
    </nav>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Category</h5>
                        <a href="" data-bs-toggle="modal" data-bs-target="#tambahCategory"
                            class="btn btn-primary mb-2">Tambah Data</a>
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
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
                                @foreach ($category as $item)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $item->name }}</td>
                                        <td><a href="" data-bs-toggle="modal"
                                                data-bs-target="#editCategory{{ $item->slug }}"
                                                class="btn btn-warning">Ubah</a>
                                            || <a data-bs-toggle="modal" data-bs-target="#deleteCategory{{ $item->slug }}"
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
    <div class="modal fade" id="tambahCategory" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/dashboard/category-add" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Category</label>
                            <input type="text" name="name" id="name" class="form-control" autofocus
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
    @foreach ($category as $item)
        <div class="modal fade" id="editCategory{{ $item->slug }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Category : {{ $item->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="/dashboard/category-edit/{{ $item->slug }}" method="post">
                            @csrf
                            @method('put')
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Category</label>
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
    @foreach ($category as $item)
        <div class="modal" id="deleteCategory{{ $item->slug }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Anda yakin ingin menghapus {{ $item->name }} ?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <a href="/dashboard/category-delete/{{ $item->slug }}" class="btn btn-danger">Hapus</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Vertically centered Modal-->
    @endforeach
@endsection
