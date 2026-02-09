@extends('dashboard.layouts.main')

@section('container')
    <h3>Barang</h3>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item active">Barang</li>
        </ol>
    </nav>



    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mt-3 justify-content-end">
                            <div class="col-3">

                                <a href="{{ route('product.add') }}" class="float-end btn btn-sm btn-primary mb-2">Tambah
                                    Data</a>
                            </div>
                        </div>
                        <h5 class="card-title">Data Barang</h5>
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <!-- Table with stripped rows -->
                        <table class="table " id="table-product">
                            <thead>
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Kategory</th>
                                    <th scope="col">Harga Satuan</th>
                                    <th scope="col">Satuan</th>
                                    <th scope="col">Stok</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>

                        </table>
                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- modal delete --}}
    {{-- @foreach ($products as $item)
        <div class="modal" id="deleteProduct{{ $item->slug }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Anda yakin ingin menghapus {{ $item->name }} ?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <a href="/dashboard/product-delete/{{ $item->slug }}" class="btn btn-danger">Hapus</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Vertically centered Modal-->
    @endforeach --}}
@endsection

@section('script')
    <script>
        var table = null;
        $(document).ready(function() {
            table = $('#table-product').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('product.datatable') }}",
                    type: 'GET',
                },
                columns: [{
                        data: 'no'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'categories_name'
                    },
                    {
                        data: 'price'
                    },
                    {
                        data: 'short'
                    },
                    {
                        data: 'stock'
                    },
                    {
                        data: 'action'
                    }

                ]
            });
        });
    </script>
@endsection
