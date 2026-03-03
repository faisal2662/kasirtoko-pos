<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Role;
use App\Models\RoleCustomer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

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
        $roleCustomer = RoleCustomer::where('is_deleted', 'N')->get();
        return view('dashboard.customer.index', ['roleCustomer' => $roleCustomer, 'customers' => $customers]);
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

        return view('dashboard.customer.create', ['id' => $id])->with('success', 'Tersimpan');
    }

    public function datatable()
    {

        $customer = Customer::where('customers.is_deleted', 'N')
            ->join('role_customers', 'customers.role_id', 'role_customers.id')
            ->select('customers.*', 'role_customers.name as role_customers_name')
            ->get();
        $no = 1;
        $update = '';
        $delete = '';
        $show = '';
        foreach ($customer as $val) {
            # code...
            $val->no = $no++;
            $update = '<span onclick="updateCustomer(' . $val->id . ')" class="badge bg-warning"> <i class="bx bx-edit"></i></span>';
            $show = '<span onclick="showData(' . $val->id . ')" class="badge bg-info"> <i class="bx bx-search-alt"></i></span>';
            $delete = '<span onclick="deleteData(' . $val->id . ')" class="badge bg-danger"> <i class="bx bx-trash"></i></span>';
            $val->action =  $update . ' | ' . $delete;
        }
        return DataTables::of($customer)->escapecolumns([])->make();
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
        try {
            DB::beginTransaction();

            $validate = $request->validate([
                'name' => 'required'
            ]);

            $lastCustomer = Customer::where('is_deleted', 'N')->orderBy('id', 'desc')->first();
            $id = 0;
            if (!$lastCustomer) {
                $id = 1;
            }else{
                $id = $lastCustomer->id;
            }
            $customer = new Customer;
            $customer->name = $request->name;
            $customer->role_id = $request->role_id;
            $customer->gender = $request->gender;
            $customer->code_customer = 'CSTMR-' . $id + 1;
            $customer->phone = $request->phone;
            $customer->address = $request->address;
            $customer->created_by = Auth::user()->id;
            $customer->save();

            DB::commit();
            return response()->json(['status' => 'success', 'desc' => 'Simpan Customer Berhasil'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json(['status' => 'failed', 'message' => 'Terjadi Kesalahan', $th->getMessage()], 500);
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
        try {
            //code...
            $customers = Customer::where('id', $id)->first();
            return response()->json(['status' => 'success', 'data' => $customers], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'failed', 'message' => 'Terjadi Kesalahan'], 500);
        }

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
        try {
            DB::beginTransaction();

            $validate = $request->validate([
                'name' => 'required'
            ]);


            $customer =  Customer::where('id', $id)->first();
            $customer->name = $request->name;
            $customer->role_id = $request->role_id;
            $customer->gender = $request->gender;
            $customer->phone = $request->phone;
            $customer->address = $request->address;
            $customer->updated_by = Auth::user()->id;
            $customer->update();

            DB::commit();
            return response()->json(['status' => 'success', 'desc' => 'Update Customer Berhasil'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json(['status' => 'failed', 'message' => 'Terjadi Kesalahan', $th->getMessage()], 500);
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
        try {
            DB::beginTransaction();


            $customer = Customer::where("id", $id)->first();
            $customer->deleted_date = Carbon::now();
            $customer->deleted_by = Auth::user()->id;
            $customer->is_deleted = 'Y';
            $customer->update();

            DB::commit();
            return response()->json(['status' => 'success', 'desc' => 'Hapus Customer Berhasil'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json(['status' => 'failed', 'message' => 'Terjadi Kesalahan', $th->getMessage()], 500);
        }
    }
}
