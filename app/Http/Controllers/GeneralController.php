<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $general = Customer::where('role_id', 'UMUM')->get();
        return view('dashboard.general.index', ['general' =>$general]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        $id = Customer::latest('created_at')->first()->code_customer;
        // dd($id);
        return view('dashboard.general.create' ,['id' => $id]);

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
        'name' => 'required'
    ]);

    $request['role_id'] = 'UMUM';
    $kode = $request->code_customer;
    $kode = explode('-', $kode);
    $kode = $kode[1] += 1;
    $request['code_customer'] = 'PLGN-00'. $kode;
    // dd($kode);    
    Customer::create($request->all());
    return redirect('dashboard/general')->with('success', 'Data Berhasil Menyimpan');
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
        
        $general = customer::where('code_customer', $id)->first();
        return view('dashboard.general.edit', ['general' => $general]);
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
        // dd($id);
        $validateData = $request->validate([
            'name' => 'required',
            'gender' => '',
            'phone' => '',
            'address' => '',
            'role_id' => 'required'
        ]);

        Customer::where('code_customer', $id)->update($validateData);
        return redirect('/dashboard/general')->with('success', 'Ubah Data Berhasil');
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
        customer::where('code_customer', $id)->first()->delete();
        return redirect('dashboard/general')->with('success', 'Hapus Data Berhasil');
    }
}