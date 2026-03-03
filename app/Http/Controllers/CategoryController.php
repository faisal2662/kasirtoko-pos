<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $category = category::where('is_deleted', 'N')->get();
        return view('dashboard.category.index', ['category' => $category]);
    }

    public function datatable()
    {
        $categories = Category::where('is_deleted', 'N')->orderBy('id', 'desc')->get();
        $no = 1;
        $update = '';
        $delete = '';
        $show = '';
        foreach ($categories as  $value) {
            # code...
            $value->no = $no++;
            $update = '<span onclick="updatedCategory(' . $value->id . ')" class="badge bg-warning"> <i class="bx bx-edit"></i></span>';
            $show = '<span onclick="showData(' . $value->id . ')" class="badge bg-info"> <i class="bx bx-search-alt"></i></span>';
            $delete = '<span onclick="deleteData(' . $value->id . ')" class="badge bg-danger"> <i class="bx bx-trash"></i></span>';
            $value->action =  $update . ' | ' . $delete;
        }
        return DataTables::of($categories)->escapecolumns([])->make();
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

        try {

            $category = new Category;
            $category->name = $request->name;
            $category->created_by = Auth::user()->id;
            $category->save();

            return response()->json(['status' => 'success', 'desc' => 'Simpan Berhasil'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'failed', 'message' => 'Terjadi Kesalahan'], 500);
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
            $unit = Category::where('id', $id)->first();
            return response()->json(['status' => 'success', 'data' => $unit], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'failed', 'message' => 'Terjadi Kesalahan'], 500);
        }
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
        // $validated = $request->validate([
        //     'name' => 'required|unique:categories'
        // ]);

        try {
            $category = category::where('id', $id)->first();
            $category->slug = null;
            $category->name = $request->name;
            $category->updated_by = Auth::user()->id;
            $category->update();
            //code...
            return response()->json(['status' => 'success', 'desc' => 'Update Berhasil'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'failed', 'message' => 'Update Gagal'], 500);
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
        // dd($id);
        try {
            $category = Category::where('id', $id)->first();
            $category->is_deleted = 'Y';
            $category->deleted_date = Carbon::now();
            $category->deleted_by = Auth::user()->id;
            $category->update();
            return response()->json(['status' => 'success', 'desc' => 'Hapus Berhasil'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'failed', 'message' => 'Hapus Gagal'], 500);
        }
    }
}
