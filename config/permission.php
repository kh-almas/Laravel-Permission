<?php

return [
    /*
     * |--------------------------------------------------------------------------
     * | Super Admin Role email
     * | When the user is created we use random password.
     * | You need to change it by reset password
     * |--------------------------------------------------------------------------
    */
    'super_admin_email' => env('SUPER_ADMIN_EMAIL', 'superadmin@gmail.com'),

    /*
     * |--------------------------------------------------------------------------
     * | Permission Cache Time in minutes
     * |--------------------------------------------------------------------------
    */
    'permission_cache_time' => 0,
];
