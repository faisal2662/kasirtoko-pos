<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\StoreSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Html as HtmlReader;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use RealRashid\SweetAlert\Facades\Alert;

class ReportController extends Controller
{
    //
    public function index()
    {

        $reports = Sale::all();
        return view('dashboard.report.index', ['reports' => $reports]);
    }

    public function cekReport(Request $request)
    {
        $tgl = $request->all();

        $data =  Sale::whereDate('date', '>=', $tgl['awal'])->whereDate('date', '<=', $tgl['akhir'])->get();

        return json_encode($data);
    }

    public function print(Request $request)
    {
        // dd($request->all());
    }

    public function reportDay(Request $request)
    {

        // if (isset($request->filter) && $request->filter == 'true') {

        //     $sale = Sale::where('is_deleted', 'N')
        //         ->select(
        //             'date',
        //             DB::raw('SUM(total) as total_harian'),
        //             DB::raw('GROUP_CONCAT(id) as all_ids') // Menggabungkan semua ID
        //         )
        //         ->whereBetween('date', [$request->awal, $request->akhir])
        //         ->groupBy('date')
        //         ->orderBy('date', 'desc')
        //         ->get();
        //     $grandTotal = 0;
        //     foreach ($sale as $val) {
        //         # code...
        //         $grandTotal  +=  $val->total_harian;
        //         $val->grandTotal = $grandTotal;
        //         $id = explode(',', $val->all_ids);
        //         $val->jumlahTransaksi = count($id);
        //         $val->jumlahQty = saleDetail::where('sale_id', $val->all_ids)->sum('quantity');
        //     }
        //     return view('dashboard.report.day', compact('sale', 'grandTotal'));
        // }
        $sekarang = Carbon::now()->toDateString();
        $prev = Carbon::now()->subDay(7)->toDateString();
        if (isset($request->fillter) && $request->filter == 'true') {
            $sale = Sale::where('is_deleted', 'N')
                ->select(
                    'date',
                    DB::raw('SUM(total) as total_harian'),
                    DB::raw('GROUP_CONCAT(id) as all_ids') // Menggabungkan semua ID
                )
                ->whereBetween('date', [$request->awal, $request->akhir])
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->get();
        } else {

            $sale = Sale::where('is_deleted', 'N')
                ->select(
                    'date',
                    DB::raw('SUM(total) as total_harian'),
                    DB::raw('GROUP_CONCAT(id) as all_ids') // Menggabungkan semua ID
                )
                ->whereBetween('date', [$prev, $sekarang])
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->get();
        }

        $grandTotal = 0;
        $grandTransaksi = 0;
        $grandQty = 0;
        //     $saleDetail = SaleDetail::where('is_deleted', 'N')->get();
        foreach ($sale as $val) {
            # code...

            $grandTotal  +=  $val->total_harian;
            $val->grandTotal = $grandTotal;
            $id = explode(',', $val->all_ids);
            $val->jumlahTransaksi = count($id);

            $qty = saleDetail::whereIn('sale_id', [$val->all_ids])->get();

            $val->jumlahQty = saleDetail::whereIn('sale_id', [$val->all_ids])->sum('quantity');

            $grandTransaksi +=  $val->jumlahTransaksi;
            $grandQty += $val->jumlahQty;
        }


        if (isset($request->export) && $request->export == 'export-pdf') {
            $store = StoreSetting::first();
            return view('dashboard.report.day-pdf', compact('store', 'sale', 'grandTotal', 'grandTransaksi', 'grandQty'));
        } else if (isset($request->export) && $request->export == 'export-excel') {
            $store = StoreSetting::first();
            $view = View::make('dashboard.report.day-pdf',  compact('store', 'sale', 'grandTotal', 'grandTransaksi', 'grandQty'))->render();
            // Load HTML ke PhpSpreadsheet

            $reader = new HtmlReader();
            libxml_use_internal_errors(true);
            $spreadsheet = $reader->loadFromString($view);

            // Export jadi .xlsx
            $writer = new Xlsx($spreadsheet);

            return response()->streamDownload(function () use ($writer) {
                $writer->save('php://output');
            }, 'Laporan harian.xlsx', [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
        } else {

            return view('dashboard.report.day', compact('sale', 'grandTotal', 'grandTransaksi', 'grandQty'));
        }
    }

    public function reportDetail(Request $request)
    {
        // if (isset($request->filter) && $request->filter == 'true') {

        //     $sale = Sale::with('payment')
        //         ->join('users as kasir', 'kasir.id', 'sales.created_by')
        //         ->select('sales.*', 'kasir.name as kasir_name')
        //         ->where("sales.is_deleted", 'N')
        //         ->whereBetween('date', [$request->awal, $request->akhir])
        //         ->orderBy('id', 'desc')
        //         ->get();
        //     foreach ($sale as $val) {
        //         # code...
        //         $val->tanggal = Carbon::parse($val->created_at)->translatedFormat('d F Y H:i');
        //     }
        //     return view('dashboard.report.detail', compact('sale'));
        // }

        if (isset($request->filter) && $request->filter == 'true') {
            $sale = Sale::with('payment')
                ->join('users as kasir', 'kasir.id', 'sales.created_by')
                ->select('sales.*', 'kasir.name as kasir_name')
                ->where("sales.is_deleted", 'N')
                ->whereBetween('date', [$request->awal, $request->akhir])
                ->orderBy('id', 'desc')
                ->get();
        } else {

            $now = Carbon::now()->toDateString();
            $sale = Sale::with('payment')
                ->join('users as kasir', 'kasir.id', 'sales.created_by')
                ->select('sales.*', 'kasir.name as kasir_name')
                ->where("sales.is_deleted", 'N')
                ->where('date', $now)
                ->orderBy('id', 'desc')
                ->get();
        }
        foreach ($sale as $val) {
            # code...
            $val->tanggal = Carbon::parse($val->created_at)->translatedFormat('d F Y H:i');
        }
        if (isset($request->export) && $request->export == 'export-pdf') {
            $store = StoreSetting::first();
            return view('dashboard.report.detail-cetak', compact('sale', 'store'));
        } else if (isset($request->export) && $request->export == 'export-excel') {
            $store = StoreSetting::first();
            $view = View::make('dashboard.report.detail-cetak', compact('sale', 'store'))->render();
            // Load HTML ke PhpSpreadsheet

            $reader = new HtmlReader();
            libxml_use_internal_errors(true);
            $spreadsheet = $reader->loadFromString($view);

            // Export jadi .xlsx
            $writer = new Xlsx($spreadsheet);

            return response()->streamDownload(function () use ($writer) {
                $writer->save('php://output');
            }, 'Laporan harian Detail.xlsx', [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
        } else {

            return view('dashboard.report.detail', compact('sale'));
        }
    }

    public function reportSellingProduct(Request $request)
    {
        if (isset($request->filter) && $request->filter == 'true') {
            $sale = SaleDetail::with('product')

                ->where('sale_details.is_deleted', 'N')
                ->select('product_id', DB::raw('SUM(quantity) as qty'), DB::raw('SUM(price) as price'))
                ->groupBy('product_id')
                ->whereBetween('created_at', [$request->awal, $request->akhir])
                ->get();
        } else {


            $now = Carbon::now()->toDateString();
            $sale = SaleDetail::with('product')
                ->where('sale_details.is_deleted', 'N')
                ->select('product_id',  DB::raw('SUM(quantity) as qty'), DB::raw('SUM(price) as price'))
                ->groupBy('product_id')
                ->where('created_at', $now)
                ->get();
        }
        if (isset($request->export) && $request->export == 'export-pdf') {
            $store = StoreSetting::first();
            return view('dashboard.report.selling_product-cetak', compact('sale', 'store'));
        } else if (isset($request->export) && $request->export == 'export-excel') {
            $store = StoreSetting::first();
            $view = View::make('dashboard.report.selling_product-cetak', compact('sale', 'store'))->render();
            // Load HTML ke PhpSpreadsheet

            $reader = new HtmlReader();
            libxml_use_internal_errors(true);
            $spreadsheet = $reader->loadFromString($view);

            // Export jadi .xlsx
            $writer = new Xlsx($spreadsheet);

            return response()->streamDownload(function () use ($writer) {
                $writer->save('php://output');
            }, 'Laporan Penjualan Produk.xlsx', [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
        } else {

            return view('dashboard.report.selling_product', compact('sale'));
        }
        return view('dashboard.report.selling_product', compact('sale'));
    }

    public function reportKas(Request $request)
    {
        if (isset($request->filter) && $request->filter == 'true') {

            $payment = Payment::where('is_deleted', 'N')
                ->select(
                    'date_payment',
                    DB::raw("SUM(CASE WHEN method = 'cash' THEN total ELSE 0 END) as cash"),
                    DB::raw("SUM(CASE WHEN method = 'transfer' THEN total ELSE 0 END) as transfer"),
                    DB::raw("SUM(total) as total")
                )
                ->whereBetween('date_payment', [$request->awal, $request->akhir])
                ->groupBy('date_payment')
                ->orderBy('date_payment', 'desc')
                ->get();
        } else {
            $now = Carbon::now()->toDateString();
            $payment = Payment::where('is_deleted', 'N')
                ->select(
                    'date_payment',
                    DB::raw("SUM(CASE WHEN method = 'cash' THEN total ELSE 0 END) as cash"),
                    DB::raw("SUM(CASE WHEN method = 'transfer' THEN total ELSE 0 END) as transfer"),
                    DB::raw("SUM(total) as total")
                )
                ->where('date_payment', $now)
                ->groupBy('date_payment')
                ->orderBy('date_payment', 'desc')
                ->get();
        }
        if (isset($request->export) && $request->export == 'export-pdf') {
            $store = StoreSetting::first();
            return view('dashboard.report.cash-flow-cetak', compact('payment', 'store'));
        } else if (isset($request->export) && $request->export == 'export-excel') {
            $store = StoreSetting::first();
            $view = View::make('dashboard.report.cash-flow-cetak', compact('payment', 'store'))->render();
            // Load HTML ke PhpSpreadsheet

            $reader = new HtmlReader();
            libxml_use_internal_errors(true);
            $spreadsheet = $reader->loadFromString($view);

            // Export jadi .xlsx
            $writer = new Xlsx($spreadsheet);

            return response()->streamDownload(function () use ($writer) {
                $writer->save('php://output');
            }, 'Laporan Kas.xlsx', [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
        } else {

            return view('dashboard.report.cash-flow', compact('payment'));
        }
        return view('dashboard.report.cash-flow', compact('payment'));
    }

    public function reportProductPajak(Request $request)
    {

        if (isset($request->filter) && $request->filter == 'true') {
            $product = SaleDetail::whereHas('product', function ($q) {
                $q->where('is_pajak', 1);
            })
                ->with(['product' => function ($q) {
                    $q->where('is_pajak', 1);
                }])
                ->where('sale_details.is_deleted', 'N')
                ->whereBetween('created_at', [$request->awal, $request->akhir])
                ->select(
                    'product_id',

                    DB::raw('DATE(created_at) as tanggal'), // Group per hari, bukan per detik
                    DB::raw('SUM(quantity) as qty'),
                    DB::raw('SUM(unit_price * quantity) as harga_jual') // Pastikan perkalian kolom benar
                )
                ->groupBy('tanggal', 'product_id')
                ->orderBy('tanggal', 'desc')
                ->get();

            $product = $product->groupBy('tanggal');
            $product = collect($product);
        } else {
            $now = Carbon::now()->toDateString();
            $prev = Carbon::now()->subDay(7)->toDateString();
            $product = SaleDetail::whereHas('product', function ($q) {
                $q->where('is_pajak', 1);
            })
                ->with(['product' => function ($q) {
                    $q->where('is_pajak', 1);
                }])
                ->where('sale_details.is_deleted', 'N')
                ->whereBetween('created_at', [$prev, $now])
                ->select(
                    'product_id',

                    DB::raw('DATE(created_at) as tanggal'), // Group per hari, bukan per detik
                    DB::raw('SUM(quantity) as qty'),
                    DB::raw('SUM(unit_price * quantity) as harga_jual') // Pastikan perkalian kolom benar
                )
                ->groupBy('tanggal', 'product_id')
                ->orderBy('tanggal', 'desc')
                ->get();

            $product = $product->groupBy('tanggal');
            $product = collect($product);
        }
        if (isset($request->export) && $request->export == 'export-pdf') {
            $store = StoreSetting::first();
            return view('dashboard.report.product_pajak-cetak', compact('product', 'store'));
        } else if (isset($request->export) && $request->export == 'export-excel') {
            $store = StoreSetting::first();
            $view = View::make('dashboard.report.product_pajak-cetak', compact('product', 'store'))->render();
            // Load HTML ke PhpSpreadsheet

            $reader = new HtmlReader();
            libxml_use_internal_errors(true);
            $spreadsheet = $reader->loadFromString($view);

            // Export jadi .xlsx
            $writer = new Xlsx($spreadsheet);

            return response()->streamDownload(function () use ($writer) {
                $writer->save('php://output');
            }, 'Laporan Produk Pajak.xlsx', [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
        } else {

            return view('dashboard.report.product_pajak', compact('product'));
        }
        return view('dashboard.report.product_pajak', compact('product'));
    }
}
