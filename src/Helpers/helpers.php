<?php

use Almas\Permission\Support\LSPermissionEngine;

if (!function_exists('user_permission')) {
    /*
     * Get the authenticated user's permissions.
     * Call without argument to get all slugs.
     * Call with slug to check if permission exists.
     *
     * @param string|null $slug
     * @return array|bool
     */
    function user_permission(string $slug = null): array|bool
    {
        return app(LSPermissionEngine::class)->get($slug);
    }
}
