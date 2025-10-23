<?php

use Almas\Permission\Http\Controllers\DashboardController;
use Almas\Permission\Http\Controllers\PermissionController;
use Almas\Permission\Http\Controllers\RoleAndPermissionController;
use Almas\Permission\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// use Route::permission(); in web.php to register the routes
// Example: Route::permission('abc');
// This will create a route group with the prefix 'abc'
// If you don't want a prefix, just use Route::permission();
// Default prefix is 'permission'

Route::macro('permission', function (string $basePath = 'permission') {
    Route::prefix($basePath)->group(function () {
        Route::middleware(['auth'])->name('permission.')->group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

            Route::get('/role-permission', [RoleAndPermissionController::class, 'index'])->name('role_permission');
            Route::get('/role/create', [RoleAndPermissionController::class, 'create'])->name('role.create');
            Route::post('/role/store', [RoleAndPermissionController::class, 'store'])->name('role.store');
            Route::get('/role/{slug}/edit', [RoleAndPermissionController::class, 'edit'])->name('role.edit');
            Route::put('/role/{slug}/edit', [RoleAndPermissionController::class, 'update'])->name('role.update');
            Route::delete('/role/{slug}/destroy', [RoleAndPermissionController::class, 'destroy'])->name('role.destroy');

            Route::get('/role/{slug}/permission/assign', [RoleAndPermissionController::class, 'assign_permission'])->name('role.assign.permission');
            Route::post('/role/{slug}/permission/assign', [RoleAndPermissionController::class, 'save_permission'])->name('role.save.permission');

            Route::get('/permission', [PermissionController::class, 'index'])->name('permission');
            Route::get('/permission/create', [PermissionController::class, 'create'])->name('permission.create');
            Route::post('/permission/store', [PermissionController::class, 'store'])->name('permission.store');
            Route::delete('/permission/{slug}/destroy', [PermissionController::class, 'destroy'])->name('permission.destroy');

            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::get('/users/{id}/role/assign', [UserController::class, 'assign_role'])->name('users.assign.role');
            Route::post('/users/{id}/role/assign', [UserController::class, 'save_assigned_role'])->name('users.assign.role.save');
        });
    });
});
