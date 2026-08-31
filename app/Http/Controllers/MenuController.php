<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    //
    public function index()
    {
        $menu = Menu::where('is_deleted', 'N')->get();
        $menus = Menu::where('is_deleted', 'N')->whereNull('parent_id')
            ->orderBy('order')
            ->with(['children' => function ($q) {
                $q->orderBy('order');
            }])
            ->get();
        return view('dashboard.menu.index', compact('menus'));
    }

    public function create()
    {
        return view('dashboard.menu.add');
    }



    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $lastMenu = Menu::where('is_deleted', 'N')->orderBy('id', 'desc')->first();

            $menu = new Menu;
            $menu->parent_id  = $request->parent;
            $menu->name  = $request->name;
            $menu->route  = $request->route;
            $menu->icon = $request->icon;
            $menu->header  = $request->header;
            $menu->order  = $lastMenu ? $lastMenu->id : 0 + 1;
            $menu->created_by  = Auth::user()->id;
            $menu->created_date  = Carbon::now();
            $menu->save();
            //code...

            DB::commit();
            return response()->json(['status' => 'success', 'desc' => 'Simpan Berhasil'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json(['status' => 'failed', 'desc' => 'Simpan Gagal', $th->getMessage(), $request->all()], 500);
        }
    }



    public function saveOrder(Request $request)
    {
        foreach ($request->menus as $menu) {
            Menu::where('id', $menu['id'])->update([
                'parent_id' => $menu['parent_id'],
                'order'     => $menu['order']
            ]);
        }

        return response()->json(['status' => 'success']);
    }


    public function update(Request $request)
    {

        try {
            DB::beginTransaction();
            $menu  = Menu::where('id', $request->id_menu)->first();
            $menu->parent_id   = $request->parent;
            $menu->name  = $request->name;
            $menu->route  = $request->route;
            $menu->icon = $request->icon;
            $menu->header  = $request->header;
            // $menu->order  = $request->order;
            $menu->updated_by  = Auth::user()->id;
            $menu->updated_date  = Carbon::now();
            $menu->update();

            DB::commit();

            return response()->json(['status' => 'success', 'desc' => 'Update Data Berhasil'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json(['status' => 'failed', 'message' => 'Update Data Gagal : ' . $request->all(), $th->getMessage(), $th->getLine()], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            DB::beginTransaction();
            $menu = Menu::findOr($request->id);
            $menu->is_deleted  = "Y";
            $menu->deleted_by = Auth::user()->id;
            $menu->deleted_date = Carbon::now();
            $menu->update();

            DB::commit();

            return response()->json(['status' => 'success', 'desc' => 'Hapus Berhasil',], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json(['status' => 'failed', 'desc' => 'Hapus Gagal',], 500);
        }
    }

    // public function role_menu(Request $request)
    // {
    //     $role = Role::where('is_deleted', 'N')->get();

    //     return view('dashboard.menu.role', compact('role'));
    // }

    public function configRole(Request $request)
    {
        $roles = Role::where('is_deleted', 'N')->get();
        $roleId = $request->role_id ?? $roles->first()->id;

        $menus = Menu::whereNull('parent_id')
            ->where('is_deleted', 'N')
            ->with('children')
            ->orderBy('order', 'asc')
            ->get();

        $roleMenus = DB::table('role_menu')
            ->where('is_deleted', 'N')
            ->where('role_id', $roleId)
            ->pluck('menu_id')
            ->toArray();

        return view('dashboard.menu.role', compact('roles', 'menus', 'roleId', 'roleMenus'));
    }

    public function saveRole(Request $request)
    {
        try {
            DB::beginTransaction();
            //code...
            $roleId = $request->role_id;
            $menuIds = $request->menu ?? [];

            DB::table('role_menu')->where('role_id', $roleId)->delete();

            foreach ($menuIds as $menuId) {
                DB::table('role_menu')->insert([
                    'role_id' => $roleId,
                    'menu_id' => $menuId,
                    'created_date' => now(),
                    'is_deleted' => 'N'
                ]);
            }
            DB::commit();
            return response()->json(['status' => 'success', 'desc' => 'Simpan Berhasil'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json(['status' => 'failed', 'desc' => 'Simpan Gagal'], 500);
        }
    }
}
