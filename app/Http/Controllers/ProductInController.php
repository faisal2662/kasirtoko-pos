<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\ProductIn;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ProductInController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::join('categories', 'categories.slug', '=', 'products.category_id')->join('units', 'units.slug', '=', 'products.unit')->get(['products.*', 'units.short']);
        // dd($products);
        return view('/dashboard/product-in/index', ['products' => $products]);
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
        $date = Carbon::today();

        $unit = $request['unit'];
        $slug = $request['slug'];
        $stock = Product::where('slug', $slug)->first();
        $stock = $request['unit'] += $stock['stock'];
        Product::where('slug', $slug)->first()->update(['stock' => $stock]);
    $code = Str::random(3); 
        $code = $slug. '-'. $code;
        
        ProductIn::create([
            'code_product_in' => $code,
            'product_id'  => $slug,
            'date_in' => $date,
            'unit' => $unit,

        ]);
        // dd($code);

        return redirect('/dashboard/productIn')->with('success', 'Tambah Berhasil');
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
        
        return view('/dashboard/product-in/show', ['histories' => $histories , 'id' => $id]);
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
        return redirect('dashboard/productIn')->with('success','Hapus Riwayat Berhasil');


    }
}