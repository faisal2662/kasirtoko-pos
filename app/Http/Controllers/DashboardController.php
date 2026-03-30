<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function index()
    {

        $now   = Carbon::now()->toDateString();
        $category =  category::where('is_deleted', 'N')->count();
        $product =  Product::where('is_deleted', 'N')->count();
        $customer =  Customer::where('is_deleted', 'N')->count();
        $sale = Sale::where('is_deleted', 'N');
        $saleDetail = SaleDetail::where('is_deleted', 'N');
        $todaySale = (clone $sale)->where("date", $now)->sum('total');
        $todayTransactions = (clone $sale)->where("date", $now)->count();
        $todayProduct = (clone $saleDetail)->whereDate("created_at", $now)->select('product_id')->groupBy('product_id')->count();
        // top produk
        $topProducts = DB::table('sale_details')
            ->join('products', 'products.id', '=', 'sale_details.product_id')
            ->select(
                'products.name',
                DB::raw('SUM(quantity) as qty')
            )
            ->groupBy('products.name')
            ->orderByDesc('qty')
            ->limit(5)
            ->get();

        // grafik penjualan
        $salesChart = DB::table('sales')
            ->select(
                DB::raw('DATE(date) as tanggal'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy(DB::raw('DATE(date)'))
            ->orderBy('tanggal', 'asc')
            ->limit(7)
            ->get();

        // stok menipis
        $lowStock = Product::whereColumn('stock', '<=', 'min_stock')
            ->limit(5)
            ->get();

        return view('/dashboard/index',compact(
            'category', 'product', 'customer', 'sale', 'saleDetail', 'todaySale', 'todayTransactions', 'todayProduct'
            ,'topProducts', 'salesChart', 'lowStock'
        ));
    }
}
