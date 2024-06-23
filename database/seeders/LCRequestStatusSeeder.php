<?php

namespace Database\Seeders;

use App\Models\LCRequestStatus;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LCRequestStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $names = array('Request Submitted','Request Approved','Request Reverted Back','Discrepancy Identified','Discrepancy Removed','Applied to Bank','Draft Under Review','Transmission Pending','Transmitted');
       
       foreach($names as $name){
            LCRequestStatus::create(['name' => $name]);
        }
    }
}
