<?php

namespace Almas\Permission\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\Authenticatable;
use Carbon\Carbon;

class LSPermissionEngine
{
    protected ?Authenticatable $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function get($slug = null)
    {
        if (!$this->user) {
            return $slug ? false : [];
        }

        return $this->resolvePermissions($slug);
    }

    protected function resolvePermissions($slug = null)
    {
        $cacheKey = 'permissions_for_user_' . $this->user->id;
        $timestampKey = $cacheKey . '_timestamp';

        $permissions = session($cacheKey);
        $cachedAt = session($timestampKey);
        $permission_cache_time = config('permission.permission_cache_time');

        if (!$permissions || !$cachedAt || Carbon::parse($cachedAt)->addMinutes($permission_cache_time)->isPast()) {
            $permissions = DB::table('permissions')
                ->join('role_permission', 'permissions.id', '=', 'role_permission.permission_id')
                ->join('role_user', 'role_permission.role_id', '=', 'role_user.role_id')
                ->where('role_user.user_id', $this->user->id)
                ->pluck('permissions.slug')
                ->toArray();

            session([$cacheKey => $permissions]);
            session([$timestampKey => now()]);
        }

        return $slug ? in_array($slug, $permissions) : $permissions;
    }
}
