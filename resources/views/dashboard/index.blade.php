@extends('dashboard.layouts.main')

@section('container')
    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-8">
                <div class="row">
                    <!-- Customers Card -->
                    <div class="col-xxl-4 col-xl-12">

                        <div class="card info-card customers-card">

                            <div class="filter">
                                <a class="icon" href="/dashboard/customer"><i <i
                                        class="bi bi-arrow-right-circle-fill"></i></a>

                            </div>

                            <div class="card-body">
                                <h5 class="card-title">Pelanggan <span>| Jumlah</span></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $customer }}</h6>
                                        <span class=" small pt-1 fw-bold">Orang</span>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div><!-- End Customers Card -->

                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="filter">
                                <a href="/dashboard/category" class="icon">
                                    <i class="bi bi-arrow-right-circle-fill"></i>
                                </a>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Kategori <span>| Jumlah</span></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-list-task"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $category }}</h6>
                                        <span class="text-success small pt-1 fw-bold">Jenis</span>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Sales Card -->

                    <!-- Revenue Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card revenue-card">

                            <div class="filter">
                                <a class="icon" href="/dashboard/product"> <i class="bi bi-arrow-right-circle-fill"></i>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">Produk <span>| Jumlah</span></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-box"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $product }}</h6>
                                        <span class="text-success small pt-1 fw-bold">Barang</span>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Revenue Card -->

                    <hr>
                    <!-- Sales Card -->
                </div>
            </div><!-- End Left side columns -->
        </div>
        <div class="row">

            <div class="col-md-3">
                <div class="card info-card customers-card">
                    <div class="card-body">
                       <div class="card-title">

                           Penjualan Hari Ini
                    </div>
                        <h4>Rp {{ number_format($todaySale) }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card info-card">
                    <div class="card-body">
                        <div class="card-title">Jumlah Transaksi</div>
                        <h4>{{ $todayTransactions }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card info-card">
                    <div class="card-body">
                        <div class='card-title'>Produk Terjual</div>
                        <h4>{{ $todayProduct }}</h4>
                    </div>
                </div>
            </div>

        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <div class="row justify-content-center">
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Graik Penjualan</div>
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

            </div>
        </div>

        <script>
            let labels = [
                @foreach ($salesChart as $row)
                    "{{ $row->tanggal }}",
                @endforeach
            ];

            let data = [
                @foreach ($salesChart as $row)
                    {{ $row->total }},
                @endforeach
            ];

            new Chart(document.getElementById("salesChart"), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Penjualan',
                        data: data
                    }]
                }
            });
        </script>
    </section>
@endsection
