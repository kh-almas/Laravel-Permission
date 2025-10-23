<?php

namespace Almas\Permission\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PermissionCommand extends Command
{
    public $signature = 'permission';

    public $description = 'create database tables and insert static data for the Permission package';

    public function handle(): int
    {
        $email = config('permission.super_admin_email');

        if (empty($email)) {
            $this->error("Super admin email not found in env. Please set SUPER_ADMIN_EMAIL in your .env file.");
            return self::FAILURE;
        }

        $schemaPath = __DIR__ . '/data/permission.sql';
        $staticDataPath = __DIR__ . '/data/permission_data.sql';

        if (!file_exists($schemaPath)) {
            $this->error("❌ Schema SQL file not found: {$schemaPath}");
            return self::FAILURE;
        }

        try {
            DB::unprepared(file_get_contents($schemaPath));
            $this->info('✅ Permission tables checked/created successfully.');

            if (!file_exists($staticDataPath)) {
                $this->warn("⚠️ Static data SQL file not found: {$staticDataPath}");
                return self::SUCCESS;
            }

            try {
                $exists = DB::table('users')->where('email', $email)->exists();

                if (!$exists) {
                    $user_id = DB::table('users')->insertGetId([
                        'name'       => 'Super Admin',
                        'email'      => $email,
                        'password'   => Hash::make(str()->random(12)),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else{
                    $user_id = DB::table('users')->where('email', $email)->value('id');
                }

                $sqlData = file_get_contents($staticDataPath);
                DB::unprepared($sqlData);

                $super_admin_role_id = DB::table('roles')->where('slug', 'super-admin')->value('id');

                if ($super_admin_role_id){
                    DB::table('role_user')->insert([
                        'role_id'       => $super_admin_role_id,
                        'user_id'       => $user_id,
                        'attached_by'   => $super_admin_role_id,
                        'created_at'    => now(),
                    ]);
                }

                $this->info('✅ Static permission data inserted successfully.');
            } catch (\Throwable $e) {
                $this->error('❌ Failed to insert static data: ' . $e->getMessage());
            }
        } catch (\Throwable $e) {
            $this->error('❌ Error creating tables: ' . $e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
