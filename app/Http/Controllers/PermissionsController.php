<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PermissionsController extends Controller
{
    public function index()
    {
        if (Gate::allows('isSuperAdmin')) {
            $users = User::where('role', '!=', 'super_admin')->paginate(7);
        } else {
            $users = User::where('role', 'user')->get();
        }

        return view('admin.permissions.index', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        $permissions = $request->input('permissions', []);
        $user->permissions = $permissions;
        $user->save();

        if (Gate::allows('isSuperAdmin')) {
            return redirect()->route('super_admin.users.permissions.index')
                ->with('success', __('admin.user.success.permissions_updated'));
        } else {
            return redirect()->route('admin.users.permissions.index')
                ->with('success', __('admin.user.success.permissions_updated'));
        }
    }
}
