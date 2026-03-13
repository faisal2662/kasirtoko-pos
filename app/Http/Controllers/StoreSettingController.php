<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;


class StoreSettingController extends Controller
{
    //
    public function index()
    {

        $setup = StoreSetting::where('is_deleted', 'N')->first();
        return view('dashboard.setting_print.index', compact('setup'));
    }

    public function store(Request $request)
    {
        $manager = new ImageManager(new Driver());
        try {

            $setup = StoreSetting::where('id', $request->id_store_setting)->first();
            $setup->store_name = $request->store_name;
            $setup->name_printer = $request->name_printer;
            $setup->address = $request->address;
            $setup->city = $request->city;
            $setup->phone = $request->phone;
            $setup->email = $request->email;
            $setup->npwp = $request->npwp;
            $setup->footer_note = $request->footer_note;
            $setup->footer_message = $request->footer_message;
            $setup->show_logo = $request->show_logo;
            $setup->show_address = $request->show_address;
            $setup->show_phone = $request->show_phone;
            $setup->show_qris = $request->show_qris;
            $setup->show_email = $request->show_email;
            // $pathFileLogo = null;
            // if ($request->hasFile('logo') && (basename($setup->logo) != basename($request->logo))) {
            //     // Simpan ke folder 'public/bukti_transfer'
            //     $file = $request->file('logo');
            //     $namaFile = time() . '_' . $file->getClientOriginalName();
            //     $pathFileLogo = $file->storeAs('setup-print', $namaFile, 'public');
            //     $setup->logo = $pathFileLogo;
            // } else {
            //     $pathFileLogo = $setup->logo;
            // }
            // $pathFileQris = null;
            // if ($request->hasFile('qris_image') && (basename($setup->qris_image) != basename($request->qris_image))) {
            //     // Simpan ke folder 'public/bukti_transfer'
            //     $file = $request->file('qris_image');
            //     $namaFile = time() . '_' . $file->getClientOriginalName();
            //     $pathFileQris = $file->storeAs('setup-print', $namaFile, 'public');
            //     $setup->qris_image = $pathFileQris;
            // } else {

            //     $pathFileQris = $setup->qris_image;
            // }

            $pathFileLogo = null;

            if ($request->hasFile('logo') && (basename($setup->logo) != basename($request->logo))) {

                $file = $request->file('logo');

                $namaFile = time() . '_' . $file->getClientOriginalName();

                $image = $manager->read($file->getPathname());

                // resize tinggi max 120
                $image->scale(height: 120);

                Storage::disk('public')->put(
                    'setup-print/' . $namaFile,
                    (string) $image->encode()
                );

                $pathFileLogo = 'setup-print/' . $namaFile;

                $setup->logo = $pathFileLogo;
            } else {

                $pathFileLogo = $setup->logo;
            }
            $pathFileQris = null;

            if ($request->hasFile('qris_image') && (basename($setup->qris_image) != basename($request->qris_image))) {

                $file = $request->file('qris_image');

                $namaFile = time() . '_' . $file->getClientOriginalName();

                $image = $manager->read($file->getPathname());

                // resize tinggi max 120
                $image->scale(height: 120);

                Storage::disk('public')->put(
                    'setup-print/' . $namaFile,
                    (string) $image->encode()
                );

                $pathFileQris = 'setup-print/' . $namaFile;

                $setup->qris_image = $pathFileQris;
            } else {

                $pathFileQris = $setup->qris_image;
            }


            $setup->save();

            return response()->json(['status' => 'success', 'desc' => 'Data Tersimpan', 'path_logo' => $pathFileLogo, 'path_qris' => $pathFileQris], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'failed', 'message' => 'Terjadi kesalahan saat menyimpan', $th->getMessage(), $th->getLine()], 500);
        }
    }


    public function printReceipt($id)
    {

        $sale = Sale::with('saleDetail.product')->where('id', $id)->first();
        $setup = StoreSetting::first();

        try {

            $connector = new WindowsPrintConnector("POS-80");
            $printer = new Printer($connector);

            /*
        ===============================
        HEADER TOKO
        ===============================
        */

            $printer->setJustification(Printer::JUSTIFY_CENTER);

            $printer->text("Toko Makmur Jaya\n");
            $printer->text("Kosambi - Tangerang\n");
            $printer->text("Telp : 084349739\n\n");

            /*
        LOGO
        */

            $logo = EscposImage::load(public_path('storage/' . $setup->logo));
            $printer->bitImage($logo);

            $printer->text("\n================================\n");

            /*
        INFO TRANSAKSI
        */

            $printer->setJustification(Printer::JUSTIFY_LEFT);

            $printer->text("Tanggal : 2026-03-11 18:40:21\n");
            $printer->text("Kasir   : faisal\n");
            $printer->text("No. Inv : INVXXXXXXX\n");

            $printer->text("--------------------------------\n");

            /*
        HEADER TABLE
        */

            $printer->text(
                str_pad("Barang", 20) .
                    str_pad("Satuan", 10) .
                    str_pad("Qty", 6) .
                    str_pad("Total", 10) . "\n"
            );

            $printer->text("--------------------------------\n");

            /*
        DATA BARANG
        */

            $items = [
                ["name" => "Indomie Goreng", "price" => 2500, "qty" => 3],
                ["name" => "Indomie Goreng", "price" => 2500, "qty" => 3],
                ["name" => "Indomie Goreng", "price" => 2500, "qty" => 3],
            ];

            foreach ($items as $item) {

                $total = $item['price'] * $item['qty'];

                $printer->text(
                    str_pad(substr($item['name'], 0, 20), 20) .
                        str_pad(number_format($item['price']), 10, " ", STR_PAD_LEFT) .
                        str_pad($item['qty'], 6, " ", STR_PAD_LEFT) .
                        str_pad(number_format($total), 10, " ", STR_PAD_LEFT)
                        . "\n"
                );
            }

            $printer->text("\n");

            /*
        TOTAL
        */

            $printer->text(str_pad("Total", 30) . str_pad("22.000", 10, " ", STR_PAD_LEFT) . "\n");
            $printer->text(str_pad("Bayar", 30) . str_pad("25.000", 10, " ", STR_PAD_LEFT) . "\n");
            $printer->text(str_pad("Kembali", 30) . str_pad("3.000", 10, " ", STR_PAD_LEFT) . "\n");

            $printer->text("--------------------------------\n");

            /*
        FOOTER
        */

            $printer->setJustification(Printer::JUSTIFY_CENTER);

            $printer->text("Silahkan cek kembali bawaan anda sebelum pulang\n\n");
            $printer->text("Terima Kasih Sudah Berbelanja\n\n");

            /*
        IMAGE PROMO / QR
        */

            $promo = EscposImage::load(public_path('promo.png'));
            $printer->bitImage($promo);

            /*
        FEED + CUT
        */

            $printer->feed(3);
            $printer->cut();

            $printer->close();

            return "Print berhasil";
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }
    public function printStruk($sale_id)
    {
        // try {
        //code...

        $sale = Sale::with('saleDetail.product')->find($sale_id);
        // return $sale;

        // $connector = new WindowsPrintConnector("POS80 Printer");
        $connector = new WindowsPrintConnector("POS-80");
        $printer = new Printer($connector);

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("TOKO MAKMUR\n");
        $printer->text("Jl. Raya Kosambi\n");
        $printer->text("------------------------------\n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("Tanggal : " . $sale->created_at . "\n");
        $printer->text("Kasir   : " . auth()->user()->name . "\n");
        $printer->text("------------------------------\n");
        foreach ($sale->saleDetail as $item) {

            $name = $item->product->name;
            $qty = $item->quantity;
            $total = number_format($item->price);

            $printer->text($this->formatItem($name, $qty, $total));
        }

        $printer->text("------------------------------\n");
        $printer->text("TOTAL :              " . number_format($sale->total) . "\n");

        $printer->feed(2);
        $printer->text("Terima Kasih\n");

        $printer->cut();
        $printer->close();
        // } catch (\Throwable $th) {
        //     //throw $th;
        //     return response()->json(['status' => 'failed', 'message' => 'Terjadi kesalahan saat menyimpan', $th->getMessage()], 500);
        // }
    }
    private function formatItem($name, $qty, $total)
    {
        $left = 20;
        $mid = 6;
        $right = 15;

        $name = substr($name, 0, $left);

        return
            str_pad($name, $left) .
            str_pad($qty, $mid, " ", STR_PAD_LEFT) .
            str_pad($total, $right, " ", STR_PAD_LEFT) .
            "\n";
    }
}
