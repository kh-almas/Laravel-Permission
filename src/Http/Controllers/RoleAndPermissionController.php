<?php

namespace Almas\Permission\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RoleAndPermissionController
{
    protected array $excludedSlugs = [];
    public function __construct()
    {
        $this->excludedSlugs = [
            'add-permission',
            'delete-permission',
        ];
    }

    public function index()
    {
        if (!user_permission('show-role-list')) {
            abort(404);
        }

        $roles = DB::table('roles')
            ->leftJoin('users', 'roles.created_by', '=', 'users.id')
            ->orderBy('roles.serial_no', 'asc')
            ->select('roles.*', 'users.name as user_name')
            ->paginate(10);

        $userRole = DB::table('role_user')
            ->where('user_id', auth()->id())
            ->first();

        return view('Permission::backend.pages.role_permission.index', compact('roles','userRole'));
    }

    public function create()
    {
        if (!user_permission('create-role')) {
            abort(404);
        }

        return view('Permission::backend.pages.role_permission.create');
    }

    public function store(Request $request)
    {
        if (!user_permission('create-role')) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => [
                'required',
                'string',
                'max:120',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                'unique:roles,slug',
            ],
            'serial_no' => ['nullable', 'integer'],
        ], [
            'slug.regex' => 'Slug may contain only lowercase letters, numbers, and single hyphens (no spaces).',
        ]);

        DB::table('roles')->insert([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'serial_no' => $validated['serial_no'],
            'created_by' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('permission.role_permission')
            ->with('success', 'Role created successfully!');
    }

    public function edit($slug)
    {
        if (!user_permission('edit-role')) {
            abort(404);
        }

        $role = DB::table('roles')->where('slug', $slug)->first();
        return view('Permission::backend.pages.role_permission.edit', compact('role'));
    }

    public function update(Request $request, $slug)
    {
        if (!user_permission('edit-role')) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => [
                'required',
                'string',
                'max:120',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('roles', 'slug')->ignore($slug, 'slug'),
            ],
            'serial_no' => ['nullable', 'integer'],
        ], [
            'slug.regex' => 'Slug may contain only lowercase letters, numbers, and single hyphens (no spaces).',
        ]);

        DB::table('roles')
            ->where('slug', $slug)
            ->update([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'serial_no' => $validated['serial_no'],
                'updated_at' => now(),
            ]);

        return redirect()->route('permission.role_permission')
            ->with('success', 'Role updated successfully!');
    }

    public function destroy($slug)
    {
        if (!user_permission('delete-role')) {
            abort(404);
        }

        $role = DB::table('roles')->where('slug', $slug)->first();

        if (! $role) {
            return redirect()->back()->with('error', 'Role not found.');
        }

        $role_permission = DB::table('role_permission')->where('role_id', $role->id)->first();

        if ($role_permission) {
            DB::table('role_permission')->where('role_id', $role->id)->delete();
        }

        DB::table('roles')->where('slug', $slug)->delete();

        return redirect()
            ->route('permission.role_permission')
            ->with('success', 'Role deleted successfully!');
    }

    public function assign_permission($slug)
    {
        if (!user_permission('can-assign-role-permission')) {
            abort(404);
        }

        $role = DB::table('roles')->where('slug', $slug)->first();

        $permissions = DB::table('permissions')
            ->when(!empty($this->excludedSlugs), function ($query) {
                $query->whereNotIn('slug', $this->excludedSlugs);
            })
            ->get();

        $assignedPermissions = DB::table('role_permission')
            ->where('role_id', $role->id)
            ->pluck('permission_id')
            ->toArray();

        return view('Permission::backend.pages.role_permission.assign_permission', [
            'role' => $role,
            'permissions' => $permissions,
            'assignedPermissions' => $assignedPermissions,
        ]);
    }

    public function save_permission(Request $request, $slug)
    {
        if (!user_permission('can-assign-role-permission')) {
            abort(404);
        }

        $role = DB::table('roles')->where('slug', $slug)->first();

        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        DB::table('role_permission')->where('role_id', $role->id)->delete();

        if($slug == "super-admin"){
            DB::table('role_permission')->insert([
                ['role_id' => 1, 'permission_id' => 1,  'attached_by' => 1],
                ['role_id' => 1, 'permission_id' => 2,  'attached_by' => 1],
                ['role_id' => 1, 'permission_id' => 3,  'attached_by' => 1],
                ['role_id' => 1, 'permission_id' => 4,  'attached_by' => 1],
                ['role_id' => 1, 'permission_id' => 5,  'attached_by' => 1],
                ['role_id' => 1, 'permission_id' => 6,  'attached_by' => 1],
                ['role_id' => 1, 'permission_id' => 7,  'attached_by' => 1],
                ['role_id' => 1, 'permission_id' => 8,  'attached_by' => 1],
                ['role_id' => 1, 'permission_id' => 9,  'attached_by' => 1],
                ['role_id' => 1, 'permission_id' => 10, 'attached_by' => 1],
            ]);
        }

        if ($request->has('permissions')) {
            $insertData = collect($request->permissions)->map(function ($permId) use ($role) {
                return [
                    'role_id' => $role->id,
                    'permission_id' => $permId,
                    'attached_by' => auth()->id(),
                ];
            })->toArray();

            DB::table('role_permission')->insert($insertData);
        }

        return redirect()->route('permission.role.assign.permission', $slug)
            ->with('success', 'Permissions updated successfully!');
    }

}
