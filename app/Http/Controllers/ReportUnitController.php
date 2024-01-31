<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportUnitController extends Controller
{
    //

    public function index(){

        
        return view('dashboard.report-unit.index');
    }
}