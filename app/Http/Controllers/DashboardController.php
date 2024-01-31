<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\category;
use App\Models\Customer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index(){
        $category =  category::all()->count();
        $product =  Product::all()->count();
        $customer =  Customer::all()->count();
        
        return view('/dashboard/index', ['category' => $category,'product' => $product, 'customer' => $customer]);
    }
}