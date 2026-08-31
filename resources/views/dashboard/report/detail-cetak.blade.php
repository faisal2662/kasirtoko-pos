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
        <h4> Laporan Detail Transaksi </h4>
        <h5> {{ $store->store_name }} </h5>
        <h5> {{ $store->address }} - {{ $store->city }} </h5>
    </section>
    <section style="text-align:center;">
        <table class="table " border="1" cellpadding="0" cellspacing="0" style="text-align: center; margin:auto;">
            <thead>
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">No. Invoice</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Kasir</th>
                    <th scope="col">Total</th>
                    <th scope="col">Bayar</th>
                    <th scope="col">Kembali</th>
                    <th scope="col">Metode Pembayaran</th>

                </tr>
            </thead>
            <tbody>
                @php
                    $grandTotal = 0;
                    $grandCashReceiv = 0;
                    $grandCashChan = 0;

                @endphp
                @foreach ($sale as $item)
                    @php
                        $grandTotal += $item->total;
                        $grandCashReceiv += $item->cash_received;
                        $grandCashChan += $item->cash_change;

                    @endphp
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $item->code_sale }}</td>
                        <td>{{ $item->tanggal }}</td>
                        <td>{{ $item->kasir_name }}</td>
                        <td>@currency($item->total) </td>
                        <td>@currency($item->cash_received) </td>
                        <td>@currency($item->cash_change) </td>
                        <td> {{ $item->payment->method }} </td>

                    </tr>
                @endforeach
                <tr>
                    <td colspan="8"></td>
                </tr>
                <tr>
                    <td colspan="4"><b>Grand Total</b></td>
                    <td> @currency($grandTotal) </td>
                    <td> @currency($grandCashReceiv) </td>
                    <td colspan="2"> @currency($grandCashChan) </td>
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
