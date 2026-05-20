<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\AdminRole;
use App\Models\AdminRolePermission;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Super Admin Role
        $superAdminRole = AdminRole::create([
            'name' => 'super_admin',
            'display_name' => 'システム管理者',
            'description' => '全ての操作が可能な最高権限です。',
        ]);

        // 2. Assign All Permissions to Super Admin
        $permissions = [
            'product_view', 'product_edit', 'product_delete',
            'order_view', 'order_edit',
            'cms_edit',
            'admin_manage',
            'system_settings'
        ];

        foreach ($permissions as $key) {
            AdminRolePermission::create([
                'admin_role_id' => $superAdminRole->id,
                'permission_key' => $key,
            ]);
        }

        // 3. Create First Super Admin User
        Admin::create([
            'name' => 'EC-NEXT 管理者',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'admin_role_id' => $superAdminRole->id,
        ]);
    }
}
