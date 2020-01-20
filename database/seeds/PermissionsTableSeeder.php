<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_roles')->insert([
            [
                'user_id' => 1,
                'role_id' => 1,
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
        ]);

        DB::table('role_permissions')->insert([
            [
                'role_id' => 1,
                'permission_id' => 1,
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
            [
                'role_id' => 1,
                'permission_id' => 2,
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
            [
                'role_id' => 1,
                'permission_id' => 3,
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
            [
                'role_id' => 1,
                'permission_id' => 4,
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
            [
                'role_id' => 1,
                'permission_id' => 5,
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
            [
                'role_id' => 1,
                'permission_id' => 6,
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
            [
                'role_id' => 1,
                'permission_id' => 7,
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
            [
                'role_id' => 1,
                'permission_id' => 8,
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
            [
                'role_id' => 1,
                'permission_id' => 9,
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
            [
                'role_id' => 1,
                'permission_id' => 10,
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
            [
                'role_id' => 1,
                'permission_id' => 11,
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
            [
                'role_id' => 1,
                'permission_id' => 12,
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
            [
                'role_id' => 1,
                'permission_id' => 13,
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
            [
                'role_id' => 1,
                'permission_id' => 14,
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
        ]);

        DB::table('roles')->insert([
            [
                'name' => 'admin',
                'description' => 'Admin',
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
            [
                'name' => 'operator',
                'description' => 'Operator',
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => 'sales',
                'description' => 'Sales',
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
        ]);

        DB::table('permissions')->insert([
            [
                'name' => 'access.inventory',
                'description' => 'Access Inventory',
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
            [
                'name' => 'access.order',
                'description' => 'Access Order',
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
            [
                'name' => 'access.product',
                'description' => 'Access Product',
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
            [
                'name' => 'access.customer',
                'description' => 'Access Customer',
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
            [
                'name' => 'access.report',
                'description' => 'Access Report',
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
            [
                'name' => 'access.setting',
                'description' => 'Access Setting',
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
            [
                'name' => 'access.template',
                'description' => 'Access Template',
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
            [
                'name' => 'access.category',
                'description' => 'Access Category',
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
            [
                'name' => 'access.ordertype',
                'description' => 'Access Order Type',
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
            [
                'name' => 'access.country',
                'description' => 'Access Country',
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
            [
                'name' => 'access.currency',
                'description' => 'Access Currency',
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
            [
                'name' => 'access.user',
                'description' => 'Access User',
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
            [
                'name' => 'access.dashboard',
                'description' => 'Access Dashboard',
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
            [
                'name' => 'access.role',
                'description' => 'Access Role',
                'created_by' => 'system',
                'updated_by' => 'system',

            ],
        ]);
    }
}
