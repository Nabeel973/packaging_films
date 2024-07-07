<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = array('PKR', 'USD', 'EUR', 'GBP', 'JPY', 'AED', 'SAR');

        foreach($currencies as $currency){
            Currency::create(['name' => $currency]);
        }
    }
}
