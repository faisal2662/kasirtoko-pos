<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ReportController extends Controller
{
    //
    public function index(){
        
        $reports = Sale::all();
        return view('dashboard.report.index', ['reports' => $reports]);
    }

    public function cekReport(Request $request){
        $tgl = $request->all();
        
       $data =  Sale::whereDate('date','>=', $tgl['awal'])->whereDate('date','<=', $tgl['akhir'])->get();

        return json_encode($data);
    }

    public function print(Request $request){
        dd($request->all());
    }
}