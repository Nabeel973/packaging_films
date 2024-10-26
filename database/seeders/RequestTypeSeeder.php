<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RequestTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $requests = array('Import Advances','Open Account','Cash Against Documents','FOC');
        foreach ($requests as $request) {
            RequestTypeSeeder::create(['name' => $request]);
        }

    }
}
