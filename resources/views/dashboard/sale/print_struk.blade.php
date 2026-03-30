<!DOCTYPE html>
<html>

<head>
    <title>Cetak Struk #{{ $sale->code_sale }}</title>
    <style>
        /* Pengaturan Ukuran Kertas Thermal */
        @page {
            size: 80mm auto;
            /* Jika printer 58mm, ganti ke 58mm */
            margin: 0;
        }

        * {
            color: #000 !important;
            /* -webkit-print-color-adjust: exact; */
            image-rendering: pixelated;
            shape-rendering: crispEdges;
        }

        body {
            /* font-family: 'Courier New', Courier, monospace; */
            /* Font struk standar */
            width: 80mm;
            margin: 0;
            font-weight: 500;
            /* padding: 10px; */
            font-size: 13px;
            /* font-family: 'Courier New', Courier, monospace; */
            -webkit-font-smoothing: none;
            /* Matikan smoothing agar tidak buram */
            /* -moz-osx-font-smoothing: grayscale; */
            font-smooth: never;
            text-rendering: optimizeSpeed;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: left;
        }

        hr {
            border-top: 1px dashed #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Hilangkan elemen saat diprint */
        @media print {
            .no-print {
                display: none;
            }

            @page {
                margin: 0;
            }

            body {
                margin: 5px;
                /* Sesuaikan jika teks terlalu mepet ke pinggir */
            }
        }
    </style>
</head>

<body>


    <p class="text-center" style="font-size:16px;font-weight:bold;"> {{ $setup->store_name }} </p>
    <p class="text-center">
        {{ $setup->show_address == 1 ? $setup->address . ' - ' . $setup->city : '' }} </p>
    <p class="text-center">
        {{ $setup->show_email == 1 ? 'Email : ' . $setup->email : '' }} </p>
    <p class="text-center" ">
        {{ $setup->show_phone == 1 ? 'Telp : ' . $setup->phone : '' }} </p>

    {{-- @if ($setup->show_logo)
        <p class="text-center">
            <image height="100px" style="text-align:center;" src="{{ asset('storage/' . $setup->logo) }}">
        </p>
    @endif --}}
    <p class="text-center">

        ==================================
    </p>
    <p>

        Pelanggan <span style="margin-left:10px;">: {{ $sale->name_customer }} </span>
    </p>
    <p>

        Tanggal <span style="margin-left:10px;">: {{ $sale->created_at }} </span>
    </p>
    <p>

        Kasir <span style="margin-left:10px;">: {{ $sale->kasir_name }} </span>
    </p>
    <p>

        No. Inv <span style="margin-left:10px;">: {{ $sale->code_sale }}</span>
    </p>
    <p class="text-center">

        ------------------------------------------------------------
    </p>
    <table>
        <thead>
            <tr>
                <td align="center" width="200">Barang</td>
                <td align="center" width="100">Satuan</td>
                <td align="center" width="100">Qty</td>
                <td align="center" width="100">Total</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="4" class="text-center">
                    ------------------------------------------------------------ </td>
            </tr>
            @foreach ($sale->saleDetail as $item)
                <tr>
                    <td> {{ substr($item->product->name, 0, 16) }} </td>
                    <td align="right"> {{ number_format($item->unit_price, 0, ',', '.') }} </td>
                    <td align="center"> {{ $item->quantity }} </td>
                    <td align="right"> {{ number_format($item->price, 0, ',', '.') }} </td>
                </tr>
            @endforeach

            <tr>
                <td colspan="4" class="text-center">
                    ------------------------------------------------------------ </td>
            </tr>

        </tbody>
    </table>
    <p>Sub Total<span style="margin-left:150px">{{ number_format($sale->sub_total) }}</span> </p>
    <p>Diskon <span style="margin-left:150px">{{ $sale->disc ? $sale->disc : '' }} &nbsp; %</span> </p>
    <p>Potongan Harga <span style="margin-left:100px">{{ number_format($sale->diskon_price, 0, ',', '.') }} </span>
    </p>
    <p>Total <span style="margin-left:170px">{{ number_format($sale->total, 0, ',', '.') }} </span> </p>
    <p>Bayar <span style="margin-left:165px">{{ number_format($sale->cash_received, 0, ',', '.') }} </span> </p>
    <p>Kembalian <span style="margin-left:135px">{{ number_format($sale->cash_change, 0, ',', '.') }} </span> </p>

    <p class="text-center"> ------------------------------------------------------------ </p>
    <p class="text-center"> {{ $setup->footer_note }} </p>
    <p class="text-center"> {{ $setup->footer_message }} </p>
    {{-- @if ($setup->show_qris)
        <p class="text-center">
            <image height="100px" style="text-align:center;" src="{{ asset('storage/' . $setup->qris_image) }}">
        </p>
    @endif --}}
    <script>
        window.onload = function() {
            // Pastikan fokus ke window ini sebelum print
            // window.focus();
            window.print();

            // Setelah print selesai, kita kosongkan iframe agar bisa dipakai lagi
            // window.onafterprint = function() {
            //     window.location.href = "about:blank";
            // };
        }
    </script>
</body>

</html>
