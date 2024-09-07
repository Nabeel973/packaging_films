<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payments = array('Advance','Sight','Deferred (30)','Deferred (60)','Deferred (90)','Deferred (120)');

        foreach($payments as $payment ){
            Payment::create(['name' => $payment]);
        }

    }
}
