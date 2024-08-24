<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $names = array('IPAK','CPAK','PETPAK','GPAK');
       foreach($names as $name){
            Company::create(['name' => $name, 'status' => 1]);
       }
    }
}
