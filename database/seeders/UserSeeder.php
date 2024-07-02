<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //User::factory()->count(100)->create();
        DB::table('users')->insert([
            [
                'name' => 'Mohsin Anwer',
                'email' => 'testing1@ipak.com.pk',
                'password' => Hash::make('12345678'), // Don't store plain text passwords
                'remember_token' => Str::random(10),
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nabeel Siddiqui',
                'email' => 'nabeelsiddiqui324@gmail.com',
                'password' => Hash::make('12345678'),
                'remember_token' => Str::random(10),
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Noraiz",
                'email' => "noraizz.test@gmail.com",
                'password' => Hash::make('12345678'),
                'remember_token' => Str::random(10),
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Syed Haris Saleem",
                'email' => "testing2@ipak.com.pk",
                'password' => Hash::make('12345678'),
                'remember_token' => Str::random(10),
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Muhammad Adnan",
                'email' => "testing3@ipak.com.pk",
                'password' => Hash::make('12345678'),
                'remember_token' => Str::random(10),
                'role_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Faraz Ahmed",
                'email' => "testing4@ipak.com.pk",
                'password' => Hash::make('12345678'),
                'remember_token' => Str::random(10),
                'role_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Muhammad Imran",
                'email' => "testing5@ipak.com.pk",
                'password' => Hash::make('12345678'),
                'remember_token' => Str::random(10),
                'role_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more users as needed
        ]);
    }
}
