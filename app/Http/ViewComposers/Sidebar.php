<?php

namespace App\Http\ViewComposers;

use App\Models\Menu;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class Sidebar{
    public function compose(View $view){
        // $menu = Menu::where('is_deleted', 'N')->whereNull('parent_id')
        //     ->orderBy('order')
        //     ->with(['children'
        //     ])
        //     ->get();
        $roleId = Auth::user()->role_id;

    $menu = Menu::whereNull('parent_id')
        ->where('is_deleted', 'N')
        ->whereIn('id', function ($query) use ($roleId) {
            $query->select('menu_id')
                  ->from('role_menu')
                  ->where('role_id', $roleId)
                  ->where('is_deleted', 'N');
        })
        ->with(['children' => function ($q) use ($roleId) {
            $q->whereIn('id', function ($query) use ($roleId) {
                $query->select('menu_id')
                      ->from('role_menu')
                      ->where('role_id', $roleId)
                      ->where('is_deleted', 'N');
            })
            ->orderBy('order', 'asc');
        }])
        ->orderBy('order', 'asc')
        ->get();
            // $user = User::join('t_role', 'users.role', 't_role.id')->where('users.id', Auth::user()->id)->select('t_role.name as role_name', 'users.*')->first();
        $view->with(['menus' => $menu]);
    }
}
?>
