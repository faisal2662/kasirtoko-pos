<?php

namespace App\Http\Controllers;

use DataTables;
use Carbon\Carbon;

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
        $products = Product::join('categories', 'categories.id', '=', 'products.category_id')->join('units', 'units.id', '=', 'products.unit_id')
            ->where('products.is_deleted', 'N')
            ->select('products.*', 'categories.name as categories_name', 'units.short')
            ->orderBy('products.id', 'desc')
            ->get();
        $no = 1;
        $update = '';
        $delete = '';
        $show = '';
        foreach ($products as $product) {
            # code...
            $product->no = $no++;
            $product->purchase_price = number_format($product->purchase_price, 0, '.', ',');
            $product->selling_price = number_format($product->selling_price, 0, '.', ',');
            $update = '<a href="' . route('product.edit', $product->id) . '" class="badge bg-warning"> <i class="bx bx-edit"></i></a>';
            $show = '<span onclick="showData(' . $product->id . ')" class="badge bg-info"> <i class="bx bx-search-alt"></i></span>';
            $delete = '<span onclick="deleteData(' . $product->id . ')" class="badge bg-danger"> <i class="bx bx-trash"></i></span>';
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
        $category = category::where('is_deleted', "N")->get();
        $units = Unit::where('is_deleted', 'N')->get();
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
            'unit' => 'required',
            'content_per_unit' => 'required',
            'purchase_price' => 'required',
            'selling_price' => 'required',
            'purchase_unit_id' => 'required',
            'min_stock' => 'required',
            'is_pajak' => 'required'
        ]);

        try {

            $product = new Product;
            $product->category_id = $request->category_id;
            $product->name = $request->name;
            $product->purchase_price = str_replace(',', '', $request->purchase_price);
            $product->selling_price = str_replace(',', '', $request->selling_price);
            $product->content_per_unit = $request->content_per_unit;
            $product->stock = $request->stock;
            $product->purchase_unit_id = $request->purchase_unit_id;
            $product->min_stock = $request->min_stock;
            $product->unit_id = $request->unit;
            $product->is_pajak = $request->is_pajak;
            $product->created_by = Auth::user()->id;
            $product->save();

            return response()->json(['status' => 'success', 'desc' => 'Simpan Produk Berhasil'], 200);
            return redirect()->route('product.add')->with('success', 'Simpan Berhasil');
        } catch (\Throwable $th) {
            return response()->json(['status' => 'failed', 'message' => 'Simpan Produk Gagal', $th->getMessage()], 500);
            throw $th;
            return redirect()->route('product.add')->with('success', 'Simpan Berhasil');
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
        try {
            $product = Product::join('categories', 'categories.id', 'products.category_id')
                ->leftJoin('units', 'units.id', 'products.unit_id')
                ->leftJoin('units as purchase_unit', 'products.purchase_unit_id', 'purchase_unit.id')
                ->select('products.*', 'categories.name as category_name', 'purchase_unit.name as purchase_unit_name', 'units.name as unit_name')->where('products.id', $id)->first();
            return response()->json(['status' => 'success', 'data' => $product], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'success', 'message' => $th->getMessage()], 500);
        }
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
        $units = Unit::where('is_deleted', 'N')->get();
        $categories = Category::where('is_deleted', "N")->get();
        // $products = Product::where('slug', $id)->get();
        $products = Product::where('products.id', $id)
            ->first();

        return view('dashboard.product.edit', ['product' => $products, 'categories' => $categories, 'units' => $units]);
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

              $request->validate([
            'name' => 'required|unique:products',
            'category_id' => 'required',
            'unit' => 'required',
            'content_per_unit' => 'required',
            'purchase_price' => 'required',
            'selling_price' => 'required',
            'purchase_unit_id' => 'required',
            'min_stock' => 'required',
            'is_pajak' => 'required'
        ]);

        try {

            $product =  Product::where('id', $id)->first();
            $product->category_id = $request->category_id;
            $product->name = $request->name;
            $product->purchase_price = str_replace(',', '', $request->purchase_price);
            $product->selling_price = str_replace(',', '', $request->selling_price);
            $product->content_per_unit = $request->content_per_unit;
            $product->stock = $request->stock;
            $product->purchase_unit_id = $request->purchase_unit_id;
            $product->min_stock = $request->min_stock;
            $product->unit_id = $request->unit;
            $product->is_pajak = $request->is_pajak;
            $product->updated_by = Auth::user()->id;
            $product->update();

            return response()->json(['status' => 'success', 'desc' => 'Update Produk Berhasil'], 200);
            return redirect()->route('product.add')->with('success', 'Simpan Berhasil');
        } catch (\Throwable $th) {
            return response()->json(['status' => 'failed', 'message' => 'Update Produk Gagal', $th->getMessage()], 500);
            throw $th;
            return redirect()->route('product.add')->with('success', 'Simpan Berhasil');
        }
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
        try {
            $product = Product::where('id', $id)->first();
            $product->is_deleted = 'Y';
            $product->deleted_date = Carbon::now();
            $product->deleted_by  = Auth::user()->id;
            $product->update();
            return response()->json(['status' => 'success', 'desc' => 'Delete Berhasil'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'failed', 'desc' => 'Delete Gagal'], 500);
        }
    }
}
