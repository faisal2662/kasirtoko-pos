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
        <h4> Laporan Kas </h4>
        <h5> {{ $store->store_name }} </h5>
        <h5> {{ $store->address }} - {{ $store->city }} </h5>
    </section>
    <section style="text-align:center;">
        <table class="table " border="1" cellpadding="0" cellspacing="0" style="text-align: center; margin:auto;">
            <thead>
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Cash</th>
                    <th scope="col">Transfer</th>
                    <th scope="col">Total</th>

                </tr>
            </thead>
            <tbody>
                @php
                    $grandTotal = 0;
                    $grandCash = 0;
                    $grandTransfer = 0;
                @endphp
                @foreach ($payment as $item)
                    @php
                        $grandTotal += $item->total;
                        $grandCash += $item->cash;
                        $grandTransfer += $item->transfer;

                    @endphp
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $item->date_payment }}</td>
                        <td>@currency($item->cash) </td>
                        <td>@currency($item->transfer) </td>
                        <td>@currency($item->total) </td>

                    </tr>
                @endforeach
                <tr>
                    <td colspan="5"></td>
                </tr>
                <tr>
                    <td colspan="2"> Total </td>
                    <td> @currency($grandCash) </td>
                    <td> @currency($grandTransfer) </td>
                    <td> @currency($grandTotal) </td>
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
