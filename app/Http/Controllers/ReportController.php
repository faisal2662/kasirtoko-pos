<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        dd($request->all());
    }

    public function reportDay(Request $request)
    {
        if (isset($request->filter) && $request->filter == 'true') {

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
            $grandTotal = 0;
            foreach ($sale as $val) {
                # code...
                $grandTotal  +=  $val->total_harian;
                $val->grandTotal = $grandTotal;
                $id = explode(',', $val->all_ids);
                $val->jumlahTransaksi = count($id);
                $val->jumlahQty = saleDetail::where('sale_id', $val->all_ids)->sum('quantity');
            }
            return view('dashboard.report.day', compact('sale', 'grandTotal'));
        }
        $sekarang = Carbon::now()->toDateString();
        $prev = Carbon::now()->subDay(7)->toDateString();

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

            return [$qty, $val];
            $val->jumlahQty = saleDetail::whereIn('sale_id', [$val->all_ids])->sum('quantity');

            $grandTransaksi +=  $val->jumlahTransaksi;
            $grandQty += $val->jumlahQty;
        }
        return $sale;
        return view('dashboard.report.day', compact('sale', 'grandTotal', 'grandTransaksi', 'grandQty'));
    }

    public function reportDetail(Request $request)
    {
        if (isset($request->filter) && $request->filter == 'true') {

            $sale = Sale::with('payment')
                ->join('users as kasir', 'kasir.id', 'sales.created_by')
                ->select('sales.*', 'kasir.name as kasir_name')
                ->where("sales.is_deleted", 'N')
                ->whereBetween('date', [$request->awal, $request->akhir])
                ->orderBy('id', 'desc')
                ->get();
            foreach ($sale as $val) {
                # code...
                $val->tanggal = Carbon::parse($val->created_at)->translatedFormat('d F Y H:i');
            }
            return view('dashboard.report.detail', compact('sale'));
        }
        $now = Carbon::now()->toDateString();
        $sale = Sale::with('payment')
            ->join('users as kasir', 'kasir.id', 'sales.created_by')
            ->select('sales.*', 'kasir.name as kasir_name')
            ->where("sales.is_deleted", 'N')
            ->where('date', $now)
            ->orderBy('id', 'desc')
            ->get();
        foreach ($sale as $val) {
            # code...
            $val->tanggal = Carbon::parse($val->created_at)->translatedFormat('d F Y H:i');
        }
        return view('dashboard.report.detail', compact('sale'));
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
        // return $payment;
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
        // $product = Product::with('saleDetail')->where('is_deleted', 'N')->where('is_pajak', 1)->get();
        // return $product;
        return view('dashboard.report.product_pajak', compact('product'));
    }
}
