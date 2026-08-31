<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $units = Unit::where('is_deleted', 'N')->get();
        return view('dashboard/unit/index', ['units' => $units]);
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
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('units')->where(function ($query) {
                    return $query->where('is_deleted', 'N');
                })
            ],
            'short' => ['required',  Rule::unique('units')->where(function ($query) {
                return $query->where('is_deleted', 'N');
            })]
        ]);

        Unit::create($request->all());
        return redirect()->route('unit')->with('success', 'Simpan Berhasil');
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
        // dd($request);s
        $validated = $request->validate([
            'name' => 'required',
            'short' => 'required'
        ]);

        $unit = Unit::where('id', $id)->first();
        $unit->slug = null;
        $unit->update($request->all());

        return redirect()->back()->with('success', 'Ubah Berhasil');
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

        $unit = Unit::where('id', $id)->first();
        $unit->is_deleted = 'Y';
        $unit->deleted_date = Carbon::now();
        $unit->deleted_by = Auth::user()->id;
        $unit->update();
        // ->delete();

        return back()->with('success', 'Hapus Berhasil');
    }
}
