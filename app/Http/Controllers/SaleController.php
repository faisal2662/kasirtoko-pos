<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductOut;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\StoreSetting;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
// use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use RealRashid\SweetAlert\Facades\Alert;
// use Intervention\Image\Image;
// use alert;
class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $setup = StoreSetting::where('is_deleted', 'N')->first();
        // Alert::toast('D', 'Data Tersimpan');
        return view('dashboard/sale/index', compact('setup'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try {
            DB::beginTransaction();
            $code_sale =  $this->generateInvoiceCode();
            //code...


            if ($request->cetak) {
                dd('berhasil');
            }
            // dd('seu');

            $code = $code_sale;

            $validator = Validator::make($request->all(), [
                // 'name' => 'required',
                'product_id' => 'required',
                'qty' => 'required',
                'harga_total' => 'required'
            ]);

            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'status' => 'failed',
                    'message' => $validator->errors()
                ], 422);
            }

            $name = $request->name;
            $customer_id = NULL;
            if (isset($request->customer_id) && $request->customer_id != '') {
                $customer = Customer::where('id', $request->customer_id)->first();
                $customer_id = $customer->id;
                $name = $customer->name;
            }
            $sale =   Sale::create([
                'name' => $name,
                'code_sale' => $code,
                'date' => Carbon::now(),
                'information' => $request->catatan,
                'diskon' => $request->diskon,
                'total' => $request->jumtot,
                'cash_received' => str_replace(',', '', $request->bayar_customer),
                'sub_total' => str_replace(',', '', $request->sub_total),
                'cash_change' => str_replace(',', '', $request->kembalian),
                'diskon_price' => $request->potongan_harga ? str_replace(',', '', $request->potongan_harga) : null,
                'customer_id' => $customer_id,
                'created_by' => Auth::user()->id
            ]);

            for ($i = 0; $i < count($request->qty); $i++) {

                $baris = $request->baris[$i];
                $unit = Unit::where('id', $request->unit_used_ . $baris)->where('is_deleted', 'N')->first();
                $product = Product::where('id', $request->product_id[$i])->first();
                $qty = $request->qty[$i];
                if ($unit->type == 'MULTIPLE') {
                    $qty = $request->qty[$i] * $product->content_per_unit;
                }


                $sale_detail = new SaleDetail;
                $sale_detail->code_detail = $this->generateInvoiceDetailCode();
                $sale_detail->sale_id = $sale->id;
                $sale_detail->product_id = $request->product_id[$i];
                $sale_detail->customer_id = $customer_id;
                $sale_detail->quantity = $qty;
                $sale_detail->unit_used = $request->unit_used_ . $baris;
                $sale_detail->disc = $request->diskon;
                $sale_detail->unit_price = $request->harga_satuan[$i];
                $sale_detail->price = $request->harga_total[$i];
                $sale_detail->created_by = Auth::user()->id;
                $sale_detail->save();

                $lastProductOut = ProductOut::where('is_deleted', 'N')->orderBy('id', 'desc')->first();
                $code_product_out = 0;
                if (!$lastProductOut) {
                    $code_product_out = 1;
                } else {
                    // Ambil 3 angka terakhir, lalu tambah 1
                    $lastNumber = substr($lastProductOut->code_product_out, -3);
                    $code_product_out = str_pad((int)$lastNumber + 1, 3, '0', STR_PAD_LEFT);
                    // $code_product_out = $lastProductOut->id + 1;
                }
                $productOut = new ProductOut;
                $productOut->code_product_out = 'BRNG-OUT/' . Carbon::now()->format('Ymd') . '00' . $code_product_out;
                $productOut->sale_id = $sale->id;
                $productOut->product_id = $request->product_id[$i];
                $productOut->date = Carbon::now();
                // $productOut->created_by = Auth::user()->id;
                $productOut->save();

                // pengurangan dalam stock

                $product->decrement('stock', $qty);
            }

            $payment  = new Payment;
            $payment->code_payment = $this->generatePaymentCode();
            $payment->date_payment = Carbon::now();
            $payment->method = $request->metode_pembayaran;
            $payment->sale_id = $sale->id;
            $payment->total = $request->jumtot;
            $payment->created_by = Auth::user()->id;
            $payment->save();

            // if(isset($request->cetak) && $request->cetak == 'cetak'){

            $this->printReceipt($sale->id, str_replace(',', '', $request->sub_total), str_replace(',', '', $request->bayar_customer), str_replace(',', '', $request->kembalian));
            // }

            DB::commit();
            return response()->json(['status' => 'success', 'desc' => 'Data Tersimpan'], 200);
            Alert::toast('Data Tersimpan', 'success');
            return redirect()->back();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json(['status' => 'failed', 'message' => 'Terjadi Kesalahan dalam menyimpan', $th->getMessage(), $th->getLine(), $request->all()], 500);
            return throw $th;
        }
    }

    public function generateInvoiceDetailCode()
    {
        $date = now()->format('Ymd'); // Hasil: 20240520

        $prefix = "INV-DTL/" . $date . "/";

        // Cek transaksi terakhir pada hari ini
        $lastTransaction = SaleDetail::where('is_deleted', 'N')->where('code_detail', 'LIKE', $prefix . '%')
            ->orderBy('code_detail', 'desc')
            ->first();

        if (!$lastTransaction) {
            $number = "001";
        } else {
            // Ambil 3 angka terakhir, lalu tambah 1
            $lastNumber = substr($lastTransaction->code_detail, -3);
            $number = str_pad((int)$lastNumber + 1, 3, '0', STR_PAD_LEFT);
        }

        return $prefix . $number;
    }
    public function generateInvoiceCode()
    {
        $date = now()->format('Ymd'); // Hasil: 20240520

        $prefix = "INV/" . $date . "/";

        // Cek transaksi terakhir pada hari ini
        $lastTransaction = Sale::where('is_deleted', 'N')->where('code_sale', 'LIKE', $prefix . '%')
            ->orderBy('code_sale', 'desc')
            ->first();

        if (!$lastTransaction) {
            $number = "001";
        } else {
            // Ambil 3 angka terakhir, lalu tambah 1
            $lastNumber = substr($lastTransaction->code_sale, -3);
            $number = str_pad((int)$lastNumber + 1, 3, '0', STR_PAD_LEFT);
        }

        return $prefix . $number;
    }

    public function generatePaymentCode()
    {
        $date = now()->format('Ymd'); // Hasil: 20240520

        $prefix = "TRX-/" . $date . "/";


        // Cek transaksi terakhir pada hari ini
        $lastTransaction = Payment::where('is_deleted', 'N')->where('code_payment', 'LIKE', $prefix . '%')
            ->orderBy('code_payment', 'desc')
            ->first();

        if (!$lastTransaction) {
            $number = "001";
        } else {
            // Ambil 3 angka terakhir, lalu tambah 1
            $lastNumber = substr($lastTransaction->code_payment, -3);
            $number = str_pad((int)$lastNumber + 1, 3, '0', STR_PAD_LEFT);
        }

        return $prefix . $number;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function search(Request $request)
    {
        $data = '';
        $search = $request->get('search');
        if ($search != '') {
            $data = Product::where('products.name', 'like', '%' . $search . '%')
                ->where('products.is_deleted', 'N')
                ->leftJoin('units', 'units.id', 'products.unit_id')
                ->leftJoin('units as purchase', 'purchase.id', 'products.purchase_unit_id')
                ->select('products.*', 'units.short', 'purchase.name as purchase_name')
                ->limit(7)->get();
        }
        // else
        // if you want to show all the data
        // {
        //     $data = DB::table('categories')
        //     ->orderBy('title','asc')
        //     ->get();
        // }
        return json_encode($data);
    }

    public function result(Request $request)
    {
        $data = '';

        $search = $request->get('result');
        if ($search != '') {
            $data = Product::where('name', $search)->first();
        }

        return response()->json($data, 200);
    }

    public function print(Request $request)
    {
        // dd($request);
        // Log::info(json_encode($request->all()));
        // "berhasil";
    }

    public function getDataPopuler()
    {
        try {
            //code...
            $popularProducts = SaleDetail::select('product_id', DB::raw('SUM(quantity) as total_sold'))
                ->with(['product' => function ($q) {
                    $q->leftJoin('units', 'units.id', 'products.unit_id')
                        ->leftJoin('units as purchase', 'purchase.id', 'products.purchase_unit_id')
                        ->select('products.*', 'units.short', 'purchase.name as purchase_name')->where('products.is_deleted', 'N');
                }]) // Mengambil info nama barang dari tabel products
                ->groupBy('product_id')
                ->orderBy('total_sold', 'desc')
                ->take(15) // Ambil 5 besar
                ->get();

            return response()->json(['status' => 'success', 'data' => $popularProducts], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'failed', 'message' => 'Terjadi Kesalahan', $th->getMessage()], 500);
        }
    }
    public function printReceipt($id, $subTotal, $totalBayar, $kembali)
    {

        $sale = Sale::with('saleDetail.product')->where('id', $id)->first();
        $setup = StoreSetting::first();
        $name_customer = $sale->name;
        if($sale->name == '' || $sale->name == null){
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


                $logo = EscposImage::load(public_path('storage/' . $setup->logo));
                $printer->bitImage($logo);
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

            $printer->text(str_pad("Sub Total", 35) . str_pad(number_format($subTotal), 10, " ", STR_PAD_LEFT) . "\n");
            $printer->text(str_pad("Disc", 35) . str_pad($sale->diskon . ' %', 10, " ", STR_PAD_LEFT) . "\n");
            $printer->text(str_pad("Potongan Harga", 35) . str_pad(number_format($sale->diskon_price), 10, " ", STR_PAD_LEFT) . "\n");
            $printer->text(str_pad("Total", 35) . str_pad(number_format($sale->total), 10, " ", STR_PAD_LEFT) . "\n");
            $printer->text(str_pad("Bayar", 35) . str_pad(number_format($totalBayar), 10, " ", STR_PAD_LEFT) . "\n");
            $printer->text(str_pad("Kembalian", 35) . str_pad(number_format($kembali), 10, " ", STR_PAD_LEFT) . "\n");

            $printer->text("-----------------------------------------------\n");

            /*
        FOOTER
        */

            $printer->text("Catatan : *\n");
            $printer->text("  " . $sale->information .  "\n\n");

            $printer->setJustification(Printer::JUSTIFY_CENTER);

            $printer->text($setup->footer_note . "\n\n");
            $printer->text($setup->footer_message . "\n\n\n\n");

            /*
        IMAGE PROMO / QR
        */
            if ($setup->show_qris) {

                $qris = EscposImage::load(public_path('storage/' . $setup->qris_image));

                $printer->bitImage($qris);
            }
            $printer->text("\n");
            $printer->text("\n");

            /*
        FEED + CUT
        */
            $printer->text("---------\n");
            // $printer->text("Selesai\n"); // Tambahkan teks kecil sebagai penanda akhir data
            $printer->feed(3);
            // $printer->cut();

            // $printer->close();

            return "Print berhasil";
        } catch (\Exception $e) {
            // if (isset($printer)) {
            //     $printer->cut();

            // }
            return $e->getMessage();
        } finally {
            // Pastikan printer ditutup apa pun yang terjadi
            if ($printer) {
                // $printer->feed(3);
                $printer->cut();
                // sleep(2);
                $printer->close();
            }
        }
    }
    public function printStruk($sale_id)
    {
        // try {
        //code...

        $sale = Sale::with('saleDetail.product')->find($sale_id);


        // $connector = new WindowsPrintConnector("POS80 Printer");
        $connector = new WindowsPrintConnector("POS-80");
        $printer = new Printer($connector);

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("TOKO MAKMUR\n");
        $printer->text("Jl. Raya Kosambi\n");
        $printer->text("------------------------------\n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("Tanggal : " . $sale->created_at . "\n");
        $printer->text("Kasir   : " . Auth::user()->name . "\n");
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
