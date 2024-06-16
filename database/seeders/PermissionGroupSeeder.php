<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permisison_groups = array('User Management','Supplier Management','LC Enquiry');
        
        foreach($permisison_groups as $permisison_group){
            PermissionGroup::create(['name' => $permisison_group]);
        }
    }
}
