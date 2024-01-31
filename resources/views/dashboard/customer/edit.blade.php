@extends('dashboard.layouts.main')

@section('container')
    <h3>Ubah Data </h3>

    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item ">Pelanggan</li>
            <li class="breadcrumb-item active">Edit - Penjualan</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <section class="section">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Data Pelanggan</div>
                        <form action="/dashboard/customer-edit/{{ $customers->code_customer }}" method="POST">
                            @csrf
                            @method('put')
                            <input type="text" name="code_customer" hidden value="{{ $customers->code_customer }}">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" name="name" id="nama" required class="form-control"
                                    value="{{ $customers->name }}" autofocus>
                            </div>
                            <div class="mb-3">
                                <label for="jk" class="form-label">Jenis Kelamin</label>
                                <select name="gender" id="jk" class="form-control" required>
                                    <option disabled>== Pilih ==</option>
                                    <option @if ($customers->gender == 'perempuan') ? 'selected' : '' @endif value="Perempuan">
                                        Perempuan</option>
                                    <option @if ($customers->gender == 'laki-laki') ? 'selected' : '' @endif value="Laki-Laki">
                                        Laki - Laki</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="telpon" class="form-label">No. Telp</label>
                                <input type="number" name="phone" id="telpon" class="form-control"
                                    value="{{ $customers->phone }}">
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea name="address" id="alamat" rows="3" class="form-control">{{ $customers->address }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
