<?php

namespace Database\Seeders;

use App\Models\RequestType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RequestTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $requests = array('Import Advances','Open Account','Cash Against Documents','FOC');
        foreach ($requests as $request) {
            RequestType::create(['name' => $request]);
        }

    }
}
