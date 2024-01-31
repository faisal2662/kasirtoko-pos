<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $customers = Customer::where('role_id', 'CSTMR')->get();
        return view('dashboard.customer.index', ['customers' => $customers]);
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
      
        return view('dashboard.customer.create',['id'=>$id])->with('success', 'Tersimpan');
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
        // dd($request );
        $validate = $request->validate([
            'name' => 'required'
        ]);
        $request['role_id'] = 'CSTMR';
        $kode = explode('-',$request['code_customer']);
        $kode = $kode[1] += 1;
        // dd($kode);
        $request['code_customer']= 'PLGN-00' . $kode;
        // dd($request->all()); 

        Customer::create($request->all());

        return redirect('/dashboard/customer')->with('success', 'Berhasil menyimpan');
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
        $customers= Customer::where('code_customer', $id)->first();
        // dd($customers);
        return view('dashboard.customer.edit', ['customers' => $customers]);
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
    $validateDate = $request->validate([
        'name' => 'required',
        'gender' => '',
        'code_customer' => '',
        'phone' => '',
        'address' => ''
    ]);
    $customer = Customer::where('code_customer', $id)->update($validateDate);

    return redirect('dashboard/customer')->with('success', 'Ubah Data Berhasil');
    
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
        // dd($id);/
        Customer::where('code_customer', $id)->first()->delete();
        return redirect('dashboard/customer')->with('success', 'Hapus Data Berhasil');
    }
}