<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductIn;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductInController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::where('is_deleted', 'N')->orderBy('id', 'desc')->get();
        $suppliers = Supplier::where('is_deleted', 'N')->get();
        // dd($products);
        return view('/dashboard/product-in/index', ['suppliers' => $suppliers, 'products' => $products]);
    }

    public function getData(Request $request)
    {
        try {
            $product = Product::where('products.is_deleted', 'N')->where('products.id', $request->id_barang)->join('units', 'units.id', 'products.unit_id')
                ->select('products.*', 'units.name as unit_name', 'units.short')
                ->first();
            return response()->json(['status' => 'success', 'data' => $product], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'failed', 'desc' => 'Gagal pada saat mengambil data', $th->getMessage()], 500);
        }
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
            //code...
            $product = Product::where('id', $request->barang)->first();
            $qty = $request->unit;
            $unit_selected = $request->unit_selected;
            $totalUnit = 0;

            $totalUnit = $qty * $product->content_per_unit;

            $supplier_id = null;
            if ($request->supplier) {
                $supplier_id = $request->supplier;
            }

            ProductIn::create([
                'product_id'  => $request->barang,
                'supplier_id' => $supplier_id,
                'date_in' => Carbon::now(),
                'unit' => $qty,
                'created_by' => Auth::user()->id,

            ]);

            $product->increment('stock', $totalUnit);

            DB::commit();
            return response()->json(['status' => 'success', 'desc' => 'Simpan Berhasil'], 200);
            // dd($code);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json(['status' => 'failed', 'message' => 'Terjadi Kesalahan Dalam Menyimpan', $th->getMessage()], 500);
        }
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
        $histories = ProductIn::join('products', 'products.slug', '=', 'product_ins.product_id')->where('product_ins.product_id', $id)->get(['products.name_product', 'product_ins.*']);

        return view('/dashboard/product-in/show', ['histories' => $histories, 'id' => $id]);
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
        $awal = Carbon::now()->startOfMonth()->toDateString();
        $akhir = Carbon::now()->endOfMonth()->toDateString();

        $d = ProductIn::where('product_id', $id)->whereBetween('created_at', [$awal, $akhir])->delete();
        return redirect('dashboard/productIn')->with('success', 'Hapus Riwayat Berhasil');
    }
}
