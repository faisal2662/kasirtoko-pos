<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class SupplierController extends Controller
{
    //
    public function index()
    {
        return view('dashboard.supplier.index');
    }


    public function datatable()
    {
        $supplier = Supplier::where('is_deleted', 'N')->get();

        $no = 1;
        $update = '';
        $delete = '';
        $show = '';
        foreach ($supplier as $value) {
            # code...
            $value->no = $no++;
            $update = '<span onclick="updateData('. $value->id. ')" class="badge bg-warning"> <i class="bx bx-edit"></i></span>';
            $show = '<span onclick="showData(' . $value->id . ')" class="badge bg-info"> <i class="bx bx-search-alt"></i></span>';
            $delete = '<span onclick="deleteDataSupplier(' . $value->id . ')" class="badge bg-danger"> <i class="bx bx-trash"></i></span>';
            // $value->action = $show .  ' | ' . $update . ' | ' . $delete;
            $value->action =  $update . ' | ' . $delete;
        }
        return DataTables::of($supplier)->escapecolumns([])->make();
    }

    public function store(Request $request)
    {
        try {
            $supplier = new Supplier;
            $supplier->name = $request->name;
            $supplier->phone = $request->phone;
            $supplier->address  = $request->address;
            $supplier->created_by = Auth::user()->id;
            $supplier->save();

            return response()->json(['status' => 'success', 'desc' => 'Simpan Berhasil'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'failed', 'message' => 'Terjadi Kesalahan'], 500);
        }
    }

    public function show($id)
    {
        try {
            $supplier = Supplier::where('id', $id)->first();
            return response()->json(['status' => 'success', 'data' => $supplier], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'failed', 'mesage' => 'Terjadi Kesalahan'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $supplier = Supplier::where('id', $id)->first();
            $supplier->name = $request->name;
            $supplier->phone = $request->phone;
            $supplier->address = $request->address;
            $supplier->updated_by = Auth::user()->id;
            $supplier->update();
            return response()->json(['status' => 'success', 'desc' => 'Update Berhasil'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'failed', 'message' => 'Terjadi Kesalahan'], 500);
        }
    }

    public function destroy($id)
    {

        try {
            $supplier = Supplier::where('id', $id)->first();
            $supplier->deleted_date = Carbon::now();
            $supplier->deleted_by = Auth::user()->id;
            $supplier->is_deleted = 'Y';
            $supplier->update();

            return response()->json(['status' => 'success', 'desc' => 'Hapus Berhasil'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'failed', 'message' => 'Terjadi Kesalahan', $th->getMessage()], 500);
        }
    }
}
