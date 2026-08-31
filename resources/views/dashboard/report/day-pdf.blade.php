<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan</title>
</head>
<style>
    .title-laporan {
        text-align: center;
    }

    .table tr,
    td,
    th {
        padding: 10px;
    }
</style>

<body>

    <section class="title-laporan">
        <h4> Laporan Harian </h4>
        <h5> {{ $store->store_name }} </h5>
        <h5> {{ $store->address }} - {{ $store->city }} </h5>
    </section>
    <section style="text-align:center;">
        <table class="table " border="1" cellpadding="0" cellspacing="0" style="text-align: center; margin:auto;">
            <thead>
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Jumlah Transaksi</th>
                    <th scope="col">Jumlah Barang</th>
                    <th scope="col">Total Harian</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($sale as $item)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $item->date }}</td>
                        <td>{{ $item->jumlahTransaksi }}</td>
                        <td>{{ $item->jumlahQty }} unit</td>
                        <td align="right">@currency($item->total_harian)</td>

                    </tr>
                @endforeach

                <tr style="font-weight: bold;">
                    <td colspan="2"> Total </td>
                    <td> {{ $grandTransaksi }} </td>
                    <td> {{ $grandQty }} </td>
                    <td align="right"> @currency($grandTotal) </td>
                </tr>
            </tbody>
        </table>
    </section>
    <script>
        window.onload = function() {
            window.print();

        }
    </script>
</body>

</html>
