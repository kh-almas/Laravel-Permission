<?php

namespace Almas\Permission\Http\Controllers;

use Almas\Permission\Http\Controllers\admin\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController
{
    protected array $excludedSlugs = [];
    public function __construct()
    {
        $this->excludedSlugs = [
            'super-admin',
        ];
    }

    public function index()
    {
        if (!user_permission('show-user-list')) {
            abort(404);
        }

        $users = DB::table('users')->paginate(10);
        return view('Permission::backend.pages.user.index',compact('users'));
    }

    public function assign_role($id)
    {
        if (!user_permission('can-assign-user-role')) {
            abort(404);
        }

        $user = DB::table('users')->where('id', $id)->first();
        $roles = DB::table('roles')
            ->when(!empty($this->excludedSlugs), function ($query) {
                $query->whereNotIn('slug', $this->excludedSlugs);
            })
            ->get();
        $user_roles = DB::table('role_user')
            ->where('user_id', $id)
            ->pluck('role_id')
            ->toArray();
        return view('Permission::backend.pages.user.assign_role',compact('user','roles', 'user_roles'));
    }

    public function save_assigned_role(Request $request, $id)
    {
        if (!user_permission('can-assign-user-role')) {
            abort(404);
        }

        $user = DB::table('users')->where('id', $id)->first();
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $roleIds = $request->input('roles', []);

        DB::table('role_user')->where('user_id', $id)->delete();

        foreach ($roleIds as $roleId) {
            DB::table('role_user')->insert([
                'role_id' => $roleId,
                'user_id' => $id,
                'attached_by' => auth()->id() ?? null,
                'created_at' => now(),
            ]);
        }

        return redirect()
            ->route('permission.users.assign.role', $id)
            ->with('success', 'Roles updated successfully!');
    }
}
