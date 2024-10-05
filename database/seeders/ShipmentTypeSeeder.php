<?php

namespace Database\Seeders;

use App\Models\ShipmentType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ShipmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = array('Partial','Full');
        foreach($types as $type){
            ShipmentType::create(['name' => $type]);
        }
    }
}
