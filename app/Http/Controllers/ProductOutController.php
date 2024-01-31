<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\ProductOut;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ProductOutController extends Controller
{
    //

    public function index(){

        $result = ProductOut::join('sales', 'code_sale' , '=', 'product_outs.sale_id')->get();

      
        $title = 'Hapus Transaksi';
        $text = "Apakah Kamu Yakin Ingin Hapus ?";
        confirmDelete($title, $text);
        return view('dashboard/report-buyer/index', ['productOuts' => $result]);
    }

    public function detail(Request $request){

        $data = $request->get('code');

        // $data = SaleDetail::where('sale_id', $data)->get();
        $data = SaleDetail::join('sales', 'code_sale', '=', 'sale_details.sale_id')->where('sale_id', $data)->join('products', 'slug', '=', 'sale_details.product_id')->get();
        return json_encode($data);
    }

    public function destroy($sale_id){
        
        ProductOut::where('sale_id', $sale_id)->delete();
        SaleDetail::where('sale_id', $sale_id)->delete();
        Sale::where('code_sale', $sale_id)->delete();
        // dd($sale_id);

        Alert::toast('Data Terhapus', 'success');
        return back();
    }
}