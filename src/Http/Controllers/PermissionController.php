<?php

namespace Almas\Permission\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermissionController
{
    public function index()
    {
        if (!user_permission('show-permission-list')) {
            abort(404);
        }

        $permissions = DB::table('permissions')
            ->leftJoin('users', 'permissions.created_by', '=', 'users.id')
            ->select('permissions.*', 'users.name as user_name')
            ->orderBy('permissions.id', 'desc')
            ->paginate(10);

        return view('Permission::backend.pages.permission.index', compact('permissions'));
    }

    public function create()
    {
        if (auth()->user()->email !== config('permission.super_admin_email') || !user_permission('add-permission')) {
            abort(404);
        }

        return view('Permission::backend.pages.permission.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->email !== config('permission.super_admin_email') || !user_permission('add-permission')) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => [
                'required',
                'string',
                'max:120',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                'unique:permissions,slug',
            ],
        ], [
            'slug.regex' => 'Slug may contain only lowercase letters, numbers, and single hyphens (no spaces).',
        ]);

        $permission = DB::table('permissions')->insertGetId([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'created_by' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $super_admin_role_id = DB::table('roles')->where('slug', 'super-admin')->value('id');

        DB::table('role_permission')->insert([
            'role_id' => $super_admin_role_id,
            'permission_id' => $permission,
            'attached_by' => auth()->id()
        ]);

        return redirect()->route('permission.permission')
            ->with('success', 'Permission created successfully!');
    }

    public function destroy($slug)
    {
        if (auth()->user()->email !== config('permission.super_admin_email') || !user_permission('delete-permission')) {
            abort(404);
        }

        $permission = DB::table('permissions')->where('slug', $slug)->first();

        if (! $permission) {
            return redirect()->back()->with('error', 'Permission not found.');
        }

        DB::table('permissions')->where('slug', $slug)->delete();

        return redirect()
            ->route('permission.permission')
            ->with('success', 'Permission deleted successfully!');
    }
}
