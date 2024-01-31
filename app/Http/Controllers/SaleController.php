<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Product;
use App\Models\ProductOut;
use App\Models\SaleDetail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;
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
        // Alert::toast('D', 'Data Tersimpan');
        return view('dashboard/sale/index');
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
        // dd($request);
          if($request['cetak']){
            dd('berhasil');
        }
        // dd('seu');   
        $date = Carbon::today();
        $code = "TRX-";
        $codeEnd = Str::random(3);
        $code .= $codeEnd;
        $validated = $request->validate([
            'name' => 'required',
            'inputs.*.product_slug' => 'required',
            'inputs.*.jumbel' => 'required',
            'inputs.*.jumlah' => 'required'
        ]);
        $name = $request['name'];
        Sale::create([
            'name' => $name,
            'code_sale' => $code,
            'date' => $date,
            'information' => 'not Information',
            'disc' => $request['diskon'],
            'total' => $request['jumtot']
        ]);
        foreach ($request->inputs as $key => $value) {
            $stock = Product::where('slug', $value['product_slug'])->first()->stock;
            $stock -= $value['jumbel'];
            Product::where('slug', $value['product_slug'])->first()->update(['stock' => $stock]);
            // dd($value['jumlah']);
            SaleDetail::create([
                'sale_id'=> $code ,
                'product_id' => $value['product_slug'],
                'quantity' => $value['jumbel'],
                'price' => $value['jumlah']
            ]);

        }
    
            ProductOut::create([
                'code_product_out' => 'TRXO-' . $codeEnd,
                'sale_id' => $code ,
                'date' => $date
            ]);
            Alert::toast('Data Tersimpan', 'success');
            return redirect()->back();
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
            if($search != '')
            {
                $data = DB::table('products')->where('name_product','like','%' .$search. '%')->get();
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

    public function result (Request $request){
        $data = '';

        $search = $request->get('result');
        if ($search != ''){
            $data = Product::where('name_product', $search)->first();
        }

        return json_encode($data);
    }

    public function print(Request $request){
        // dd($request);
        // Log::info(json_encode($request->all()));
        // "berhasil";
    }
}