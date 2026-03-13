<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\StoreSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Yajra\DataTables\DataTables;

class InvoiceController extends Controller
{
    //

    public function index()
    {
        $sale = Sale::with(['saleDetail.product', 'payment', 'customer'])->where('sales.is_deleted', 'N')->orderBy('id', 'desc')->get();
        $setup = StoreSetting::first();
        return view('dashboard.invoice.index', compact('sale', 'setup'));
    }

    public function datatable()
    {

        $sale = Sale::with(['saleDetail.product', 'payment', 'customer'])
            ->leftJoin('users as kasir', 'sales.created_by', 'kasir.id')
            ->select('sales.*', 'kasir.name as kasir_name')
            ->where('sales.is_deleted', 'N')->orderBy('id', 'desc')->get();

        $no = 1;
        $update = '';
        $delete = '';
        $show = '';
        foreach ($sale as $val) {
            # code...
            $val->no = $no++;
            $name = $val->name;
            $val->keterangan = '<p> Nama Kasir : ' . $val->kasir_name  . '</p><p>Tanggal Transaksi : ' . Carbon::parse($val->created_date)->toDateTimeString() . '</p>';
            if ($val->customer_id) {
                $name = $val->customer->name ?? '';
            }
            $val->name = $name;
            $val->total = number_format($val->total, 2, '.', ',');
            $val->jumlah_total = $val->saleDetail->sum('quantity');

            // $update = '<a href="' . route('product.edit', $val->id) . '" class="badge bg-warning"> <i class="bx bx-edit"></i></a>';
            $show = '<span onclick="showData(' . $val->id . ')" class="badge bg-info"> <i class="bx bx-search-alt"></i></span>';
            // $delete = '<span onclick="deleteData(' . $val->id . ')" class="badge bg-danger"> <i class="bx bx-trash"></i></span>';
            $val->action = $show;
        }
        return DataTables::of($sale)->escapecolumns([])->make();
    }

    public function show($id)
    {
        try {
            $sale = Sale::with(['saleDetail.product', 'payment', 'customer'])
                ->leftJoin('users as kasir', 'sales.created_by', 'kasir.id')
                ->select('sales.*', 'kasir.name as kasir_name')
                ->where('sales.is_deleted', 'N')->where('sales.id', $id)->first();

            $sale->customer_name = $sale->customer ? $sale->customer->name : $sale->name;


            return response()->json(['status' => 'success', 'data' => $sale], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'failed', 'message' => "Terjadi Kesalahan", $th->getMessage()], 500);
            //throw $th;
        }
    }

    public function prePrint(Request $request)
    {
        $sale = Sale::with('saleDetail.product')->where('id', $request->id_sale)->first();
        $setup = StoreSetting::first();
        $name_customer = $sale->name;
        if ($sale->name == '' || $sale->name == null) {
            $name_customer = Customer::where('id', $sale->customer_id)->first()->name ?? '';
        }

        try {

            $connector = new WindowsPrintConnector($setup->name_printer);
            $printer = new Printer($connector);

            /*
        ===============================
        HEADER TOKO
        ===============================
        */

            $printer->setJustification(Printer::JUSTIFY_CENTER);

            $printer->text($setup->store_name . "\n");
            if ($setup->show_address) {

                $printer->text($setup->address . ' - ' . $setup->city . "\n");
            }
            if ($setup->show_email) {

                $printer->text('Email : ' . $setup->email . "\n");
            }
            if ($setup->show_phone) {

                $printer->text('Telp : ' . $setup->phone . "\n");
            }

            /*
        LOGO
        */
            if ($setup->show_logo) {


                // $logo = EscposImage::load(public_path('storage/' . $setup->logo));
                // $printer->bitImage($logo);
                $pathLogo = public_path('storage/' . $setup->logo);

                // Cek apakah file fisik benar-benar ada di folder
                if (file_exists($pathLogo)) {
                    $logo = EscposImage::load($pathLogo);
                    $printer->bitImage($logo);
                }
            }

            $printer->text("\n================================\n");

            /*
        INFO TRANSAKSI
        */

            $printer->setJustification(Printer::JUSTIFY_LEFT);

            $printer->text("Nama Pelanggan : " . $name_customer . "\n\n");
            $printer->text("Tanggal        : " . $sale->created_at . "\n");
            $printer->text("Kasir          : " . Auth::user()->name . "\n");
            $printer->text("No. Inv        : " . $sale->code_sale . "\n");

            $printer->text("-----------------------------------------------\n");

            /*
        HEADER TABLE
        */

            $printer->text(
                str_pad("Barang", 25) .
                    str_pad("Satuan", 10) .
                    str_pad("Qty", 6) .
                    str_pad("Total", 10) . "\n"
            );

            $printer->text("-----------------------------------------------\n");

            /*
        DATA BARANG
        */


            foreach ($sale->saleDetail as $item) {


                $printer->text(
                    str_pad(substr($item->product->name, 0, 20), 20) .
                        str_pad(number_format($item->unit_price), 10, " ", STR_PAD_LEFT) .
                        str_pad($item->quantity, 6, " ", STR_PAD_LEFT) .
                        str_pad(number_format($item->price), 10, " ", STR_PAD_LEFT)
                        . "\n"
                );
            }

            $printer->text("\n");

            /*
        TOTAL
        */

            $printer->text(str_pad("Sub Total", 35) . str_pad(number_format($sale->sub_total), 10, " ", STR_PAD_LEFT) . "\n");
            $printer->text(str_pad("Disc", 35) . str_pad($sale->diskon . ' %', 10, " ", STR_PAD_LEFT) . "\n");
            $printer->text(str_pad("Potongan Harga", 35) . str_pad(number_format($sale->diskon_price), 10, " ", STR_PAD_LEFT) . "\n");
            $printer->text(str_pad("Total", 35) . str_pad(number_format($sale->total), 10, " ", STR_PAD_LEFT) . "\n");
            $printer->text(str_pad("Bayar", 35) . str_pad(number_format($sale->cash_received), 10, " ", STR_PAD_LEFT) . "\n");
            $printer->text(str_pad("Kembalian", 35) . str_pad(number_format($sale->cash_change), 10, " ", STR_PAD_LEFT) . "\n");

            $printer->text("-----------------------------------------------\n");

            /*
        FOOTER
        */

            $printer->text("Catatan : *\n");
            $printer->text("  " . ($sale->information  ?? '-') .  "\n\n");

            $printer->setJustification(Printer::JUSTIFY_CENTER);

            $printer->text($setup->footer_note . "\n\n");
            $printer->text($setup->footer_message . "\n\n\n\n");

            /*
        IMAGE PROMO / QR
        */
            if ($setup->show_qris) {

                // $qriss = EscposImage::load(public_path('storage/' . $setup->qris_image));
                // $printer->bitImage($qriss);

                $pathLogo = public_path('storage/' . $setup->qris_image);

                // Cek apakah file fisik benar-benar ada di folder
                if (file_exists($pathLogo)) {
                    $qris = EscposImage::load($pathLogo);
                    $printer->bitImage($qris);
                }
            }
            $printer->text("\n");
            $printer->text("\n");

            /*
        FEED + CUT
        */
            $printer->text("---------\n");
            $printer->feed(3);
            $printer->cut();

            $printer->close();

            return "Print berhasil";
        } catch (\Exception $e) {
            // if (isset($printer)) {
            //     $printer->cut();

            // }
            return response()->json([$e->getMessage(), $e->getLine()],500);
        }
        // finally {
        //     // Pastikan printer ditutup apa pun yang terjadi
        //     if ($printer) {
        //         // $printer->feed(3);
        //         // sleep(2);
        //         $printer->cut();
        //         $printer->close();
        //     }
        // }
    }
}
