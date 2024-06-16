<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        // $roles = array('System Admin','Finance Head','Commercial Head','Treasury Officer','Commercial Officer');
        
        // foreach($roles as $role){
        //     Role::create(['name' => $role,'guard_name' =>'web']);
        // }

        DB::table('permissions')->insert([
            // [
            //     'id' => 1,
            //     'name' => 'View User List',
            //     'guard_name' => 'web',
            //     'created_at' => Carbon::now(),
            //     'updated_at' => Carbon::now(),
            //     'type' => 1,
            //     'permission_group_id' => 1
            // ],
            // [
            //     'id' => 2, // Changed to a unique ID
            //     'name' => 'Add User',
            //     'guard_name' => 'web',
            //     'created_at' => Carbon::now(),
            //     'updated_at' => Carbon::now(),
            //     'type' => 2,
            //     'permission_group_id' => 1
            // ],
            // [
            //     'id' => 3, // Changed to a unique ID
            //     'name' => 'Edit User',
            //     'guard_name' => 'web',
            //     'created_at' => Carbon::now(),
            //     'updated_at' => Carbon::now(),
            //     'type' => 2,
            //     'permission_group_id' => 1
            // ],
            // [
            //     'id' => 4, // Changed to a unique ID
            //     'name' => 'Delete User',
            //     'guard_name' => 'web',
            //     'created_at' => Carbon::now(),
            //     'updated_at' => Carbon::now(),
            //     'type' => 2,
            //     'permission_group_id' => 1
            // ],
            [
                'id' => 5,
                'name' => 'View Supplier List',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'type' => 1,
                'permission_group_id' => 2
            ],
            [
                'id' => 6, // Changed to a unique ID
                'name' => 'Add Supplier',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'type' => 2,
                'permission_group_id' => 2
            ],
            [
                'id' => 7, // Changed to a unique ID
                'name' => 'Edit Supplier',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'type' => 2,
                'permission_group_id' => 2
            ],
            [
                'id' => 8, // Changed to a unique ID
                'name' => 'Delete Supplier',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'type' => 2,
                'permission_group_id' => 2
            ],
            [
                'id' => 9,
                'name' => 'View LC List',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'type' => 1,
                'permission_group_id' => 3
            ],
            [
                'id' => 10, // Changed to a unique ID
                'name' => 'Add LC Request',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'type' => 2,
                'permission_group_id' => 3
            ],
            [
                'id' => 11, // Changed to a unique ID
                'name' => 'Edit LC Request',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'type' => 2,
                'permission_group_id' => 3
            ]

        ]);
        
    }

    
} 
