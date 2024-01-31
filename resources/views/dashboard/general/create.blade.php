@extends('dashboard.layouts.main')

@section('container')
    <h3>Data Umum</h3>

    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item">Umum</li>
            <li class="breadcrumb-item active">Tambah - Umum</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <section class="section">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Data Umum</div>
                        <form action="/dashboard/general-add" method="POST">
                            @csrf
                            <input type="text" name="code_customer" hidden value="{{ $id }}">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" name="name" id="nama" required class="form-control" autofocus>
                            </div>
                            <div class="mb-3">
                                <label for="jk" class="form-label">Jenis Kelamin</label>
                                <select name="gender" id="jk" class="form-control" required>
                                    <option selected disabled>== Pilih ==</option>
                                    <option value="Perempuan">Perempuan</option>
                                    <option value="Laki-Laki">Laki - Laki</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="telpon" class="form-label">No. Telp</label>
                                <input type="number" name="phone" id="telpon" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea name="address" id="alamat" rows="3" class="form-control"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
