<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ImportRequestStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ImportRequestStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $names = array('Request Submitted','Request Approved','Request Reverted Back','Request Adjusted','Discrepancy Identified','Discrepancy Removed','Applied to Bank','Request Completed','Shipping Documents Awaited');

        foreach ($names as $name) {
            ImportRequestStatus::create(['name' => $name]);
        }
    }
}
