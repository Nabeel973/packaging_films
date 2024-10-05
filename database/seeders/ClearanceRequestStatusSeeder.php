<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClearanceRequestStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ClearanceRequestStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clearance_statuses = array('Anticipated','Arrived','Released');
        foreach($clearance_statuses as $clearance_status){
            ClearanceRequestStatus::create(['name' => $clearance_status]);
        }
    }
}
