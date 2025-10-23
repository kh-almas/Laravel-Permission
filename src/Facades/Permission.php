<?php

namespace Almas\Permission\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Almas\Permission\Permission
 */
class Permission extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Almas\Permission\Permission::class;
    }
}
