<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\RoleCustomer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class RoleCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $role_customer = RoleCustomer::where('is_deleted', 'N')->get();

        return view('dashboard.role_customer.index', compact('role_customer'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        try {
            $role = new RoleCustomer;
            $role->name = $request->name;
            $role->created_by = Auth::user()->id;
            $role->save();

            Alert::toast('Simpan Berhasil', 'success');
            return redirect()->back();
        } catch (\Throwable $th) {
            //throw $th;
            Alert::toast('Terjadi Kesalahan', 'warning');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //

        try {
            $role = RoleCustomer::where('id', $id)->first();
            $role->name = $request->name;
            $role->updated_by = Auth::user()->id;
            $role->update();

            Alert::toast('Update Berhasil', 'success');

            return redirect()->back();
        } catch (\Throwable $th) {
            //throw $th;

            Alert::toast('Terjadi Kesalahana', 'warning');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try {
            $role = RoleCustomer::where('id', $id)->first();
            $role->deleted_date = Carbon::now();
            $role->deleted_by = Auth::user()->id;
            $role->is_deleted = 'Y';
            $role->update();

            Alert::toast('Hapus Berhasil', 'success');
            return redirect()->back();
        } catch (\Throwable $th) {
            //throw $th;
            Alert::toast('Terjadi Kesalahan', 'warning');
            return redirect()->back();
        }
    }
}
