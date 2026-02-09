<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Unit;

use App\Models\Product;
use App\Models\category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        return view('/dashboard/product/index');
    }


    public function datatables()
    {
        $products = Product::join('categories', 'categories.id', '=', 'products.category_id')->join('units', 'units.slug', '=', 'products.unit')
            ->select('products.*', 'categories.name as categories_name', 'units.short')
            ->get();
        $no = 1;
        $update = '';
        $delete = '';
        $show = '';
        foreach ($products as $product) {
            # code...
            $product->no = $no++;
            $update = '<a href="' . route('product.edit', $product->id) . '" class="badge bg-warning"> <i class="bx bx-edit"></i></a>';
            $product->action = $show .  ' | ' . $update . ' | ' . $delete;
        }
        return DataTables::of($products)->escapecolumns([])->make();
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
        return view('dashboard.product.create', ['category' => $category, 'units' => $units]);
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
            'name' => 'required|unique:products',
            'category_id' => 'required',
            'unit' => 'required'
        ]);
        $request['price']  = str_replace(',', '', $request->price);
        dd($request->all());

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
        $products = Product::where('products.id', $id)->join('categories', 'categories.id', '=', 'products.category_id')->join('units', 'units.slug', '=', 'products.unit')
        ->select('products.*', 'units.short')
        ->first();
        // dd($products);
        return view('/dashboard/product/edit', ['product' => $products, 'categories' => $categories, 'units' => $units]);
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
            'name' => 'required',

        ]);

        $product = product::where('id', $id)->first();
        // $product->slug =null;
        $product->name = $request->name;
        $product->price =  str_replace(',', '', $request->unit_price);
        $product->stock = $request->stock;
        $product->unit  = $request->unit;
        $product->category_id = $request->category_id;

        // $product->update([
        //     'name_product' => $request['name_product'],
        //     'category_id' => $request['category_id'],
        //     'unit_price' => $request['unit_price'],
        //     'stock' => $request['stock'],
        //     'unit' => $request['unit']
        // ]);
        $product->updated_by = Auth::user()->id;
        $product->update();
        return back()->with('success', 'Ubah Berhasil');
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
