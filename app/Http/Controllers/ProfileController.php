<?php

namespace App\Http\Controllers;

use App\Models\User;
// use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class ProfileController extends Controller
{
    //
    public function index(){

        // $cek = User::where
    // dd(Auth::user()->name);
        $users = User::where('username', Auth::user()->username)->get();
        return view('dashboard.profile.index', ['users' => $users]);
    } 
     public function confirmPass(Request $request){
        
        $pass = $request->get('password');
        $confirmPass = Auth::user()->password;
        
        if(Hash::check($pass, $confirmPass)){
            $data = true;
        } else{
            $data = false;
        }
        
        return json_encode($data);
     }

     public function newPass(Request $request){
        $pass = Hash::make($request['password']);

        User::where('username', Auth::user()->username)->first()->update(['password' => $pass]);

        Alert::toast('Perubahan Berhasil', 'success');
        return back();
     }

     public function update(Request $request , $id){
        // dd($request);
        User::where('username',$id)->first()->update($request->all());
        Alert::toast('Perubahan Berhasil', 'success');

        return back();
        
     }
}  