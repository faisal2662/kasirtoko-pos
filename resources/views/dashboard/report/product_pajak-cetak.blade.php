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
        <h4> Laporan Produk Kenak Pajak </h4>
        <h5> {{ $store->store_name }} </h5>
        <h5> {{ $store->address }} - {{ $store->city }} </h5>
    </section>
    <section style="text-align:center;">
        <table class="table " border="1" cellpadding="0" cellspacing="0" style="text-align: center; margin:auto;">
            <thead>
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Produk</th>
                    <th scope="col">Harga Jual</th>
                    <th scope="col">Jumlah Terjual</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $grandTotal = 0;
                    $grandQty = 0;
                    $no = 1;
                @endphp

                @foreach ($product as $tgl => $items)
                    @foreach ($items as $index => $v)
                        @php
                            $grandTotal += $v->harga_jual;
                            $grandQty += $v->qty;
                        @endphp
                        <tr>
                            {{-- Hanya munculkan No dan Tanggal di baris pertama tiap grup tanggal --}}
                            @if ($index === 0)
                                <td rowspan="{{ $items->count() }}">{{ $no++ }}</td>
                                <td style="text-align: center;vertical-align:middle;s" rowspan="{{ $items->count() }}">
                                    {{ \Carbon\Carbon::parse($tgl)->format('d/m/Y') }}</td>
                            @endif

                            <td>{{ $v->product->name ?? 'Produk Tidak Ditemukan' }}</td>
                            <td>@currency($v->harga_jual)</td>
                            <td>{{ $v->qty }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
            <tfoot>
                <tr style="font-weight: bold; background-color: #f8f9fa;">
                    <td colspan="3" align="center">TOTAL KESELURUHAN</td>
                    <td>@currency($grandTotal)</td>
                    <td>{{ $grandQty }}</td>
                </tr>
            </tfoot>
        </table>
    </section>
    <script>
        window.onload = function() {
            window.print();

        }
    </script>
</body>

</html>
