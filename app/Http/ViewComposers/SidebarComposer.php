<?php

namespace App\Http\ViewComposers;

use App\Models\Rol;
use App\Models\Tesis;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SidebarComposer
{
    public function compose(View $view)
    {
        $user = Auth::user();
        
        if (!$user) {
            $view->with('sidebarRoles', collect());
            return;
        }

        $rolesUnicos = obtenerRolesUnicosSidebar($user->id_user);

        $view->with('sidebarRoles', $rolesUnicos);
    }
}