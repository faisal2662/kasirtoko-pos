<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::join('roles', 'roles.id', '=', 'users.role_id')->get();

        $title = 'Hapus User Ini';
        $text = "Apakah Kamu Yakin Ingin Hapus ?";
        confirmDelete($title, $text);
        return view('dashboard.user.index', ['users' => $users]);
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
        // $role = Role::where('name_role', $request['status'])->first()->id;
        $validate = Validator::make($request->all(),[
            'name'=> 'required|max:50',
            'username' => 'required|unique:users',
            'role_id' =>'required',
            'password' => 'required',
            'address' => 'required'
        ],
        [
            'name' => 'name lebih dari 50 karakter',
            'username' => 'username sudah ada',
            ]
        );
        
        // $validate['status'] = $role;

        $request['password'] = Hash::make($request['password']);
        // dd($request);
            // dd($validate);
            
            if ($validate->fails()) {
                return back()->with('toast_error', $validate->messages()->all()[0])->withInput();
            }
            User::create($request->all());
            return back()->with('toast_success', 'Data Tersimpan');
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
        // dd($id);
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
        $validate = $request->validate([
            'name' => 'required|max:50',
        ]);
        User::where('username', $id)->first()->update($request->all());
        Alert::toast('Perubahan Berhasil', 'success');
        return back();
    }

    public function passUpdate(Request $request, $id)
    {
        // dd($id);
        $request['password']= Hash::make($request['password']);
        User::where('username', $id)->first()->update($request->all());
        Alert::toast('Update Password Berhasil', 'success');
        return back();
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
        User::where('username', $id)->first()->delete();
        Alert::toast('Hapus Berhasil','success');
        return back();
    }
}