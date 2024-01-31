<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Product;
use App\Models\category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $products = Product::join('categories', 'categories.slug', '=', 'products.category_id')->join('units', 'units.slug' , '=', 'products.unit')->get(['products.*', 'units.short']);
        // dd($products);
        return view('/dashboard/product/index', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()

    {
        //
        $category = category::all();
        $units = Unit::all();
        return view('dashboard.product.create' , ['category' => $category, 'units' => $units]);
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
        // dd($request);
        $request->validate([
            'name_product' => 'required|unique:products',
            'category_id' => 'required',
            'unit' => 'required'
        ]);
        Product::create($request->all());
        return redirect('/dashboard/product-add')->with('success', 'Simpan Berhasil');
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
        // dd($id);
        $units = Unit::get();
        $categories = Category::get();
        // $products = Product::where('slug', $id)->get();
        $products = Product::where('products.slug', $id)->join('categories', 'categories.slug', '=', 'products.category_id')->join('units', 'units.slug', '=', 'products.unit')->get(['products.*', 'units.short']);
        // dd($products);
        return view('/dashboard/product/edit', ['products' => $products , 'categories' => $categories, 'units' => $units]);
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
        // dd($request);
     
        $validate = $request->validate([
            'name_product' => 'required',
            
        ]);

        $product = product::where('slug', $id)->first();
        $product->slug = null;
        // $product->update([
        //     'name_product' => $request['name_product'],
        //     'category_id' => $request['category_id'],
        //     'unit_price' => $request['unit_price'],
        //     'stock' => $request['stock'],
        //     'unit' => $request['unit']
        // ]);
        $product->update($request->all());
        return redirect('/dashboard/product')->with('success', 'Ubah Berhasil');

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
        Product::where('slug', $id)->first()->delete();
        return redirect('/dashboard/product')->with('success', 'Hapus Berhasil');
    }
}